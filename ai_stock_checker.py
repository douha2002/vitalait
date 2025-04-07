import mysql.connector
from apscheduler.schedulers.background import BackgroundScheduler
from apscheduler.triggers.cron import CronTrigger
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.application import MIMEApplication
from fpdf import FPDF
import logging
from datetime import datetime
import os

# ======================
# CONFIGURATION
# ======================

# Database Configuration (matches your Laravel setup)
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',      # Your MySQL username
    'password': '',      # Your MySQL password
    'database': 'it_park'  # Your database name
}

# Email Configuration (SMTP settings)
EMAIL_CONFIG = {
    'smtp_server': 'smtp.gmail.com',
    'smtp_port': 587,
    'email': 'douha.hm17@gmail.com',  # Your sending email
    'password': 'iwsewbpeawxkcfod'   # App password for Gmail
}

# ======================
# CORE FUNCTIONALITY
# ======================

def setup_logging():
    """Configure logging to file and console"""
    logging.basicConfig(
        level=logging.INFO,
        format='%(asctime)s - %(levelname)s - %(message)s',
        handlers=[
            logging.FileHandler('stock_alerts.log'),
            logging.StreamHandler()
        ]
    )
    return logging.getLogger(__name__)

logger = setup_logging()

class PDFReport(FPDF):
    """Custom PDF generator for stock alerts"""
    def header(self):
        self.set_font('Arial', 'B', 14)
        self.cell(0, 10, 'Demande d\'Achat - Stock Critique', 0, 1, 'C')
        self.ln(5)
        self.set_font('Arial', '', 10)
        self.cell(0, 10, f'Généré le {datetime.now().strftime("%d/%m/%Y à %H:%M")}', 0, 1, 'C')
        self.ln(10)
    
    def footer(self):
        self.set_y(-15)
        self.set_font('Arial', 'I', 8)
        self.cell(0, 10, f'Page {self.page_no()}', 0, 0, 'C')

def generate_pdf_report(items):
    """Generate PDF report of low stock items"""
    pdf = PDFReport()
    pdf.add_page()
    
    # Table header
    pdf.set_font('Arial', 'B', 11)
    col_widths = [70, 40, 40, 40]
    headers = ['Équipement', 'Quantité','Statut']
    
    for i, header in enumerate(headers):
        pdf.cell(col_widths[i], 10, header, border=1)
    pdf.ln()
    
    # Table rows
    pdf.set_font('Arial', '', 10)
    for item in items:
        status = "URGENT" if item['quantite'] <= 2 else "ATTENTION"
        
        pdf.cell(col_widths[0], 10, item['sous_categorie'], border=1)
        pdf.cell(col_widths[1], 10, str(item['quantite']), border=1)
        pdf.cell(col_widths[3], 10, status, border=1)
        pdf.ln()
    
    filename = f"stock_alert_{datetime.now().strftime('%Y%m%d_%H%M%S')}.pdf"
    pdf.output(filename)
    return filename

def get_all_user_emails():
    """Fetch all emails from users table (matches your Laravel schema)"""
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor(dictionary=True)
        
        cursor.execute("SELECT email FROM users WHERE email IS NOT NULL")
        return [row['email'] for row in cursor.fetchall()]
        
    except mysql.connector.Error as err:
        logger.error(f"Database error: {err}")
        return []
    finally:
        if 'conn' in locals() and conn.is_connected():
            conn.close()

def send_email_with_pdf(recipients, pdf_filename):
    """Send email with PDF attachment to all recipients"""
    try:
        # Create message container
        msg = MIMEMultipart()
        msg['From'] = EMAIL_CONFIG['email']
        msg['Subject'] = "⚠ Alerte Stock - Équipements en Rupture"
        
        # Email body in French
        body = """
        Bonjour,
        
        Le système a détecté des équipements dont le stock est critique.
        
        Veuillez trouver ci-joint la liste des équipements nécessitant 
        une réapprovisionnement urgent.
        
        Cordialement,
        Service Gestion de Stock
        """
        msg.attach(MIMEText(body, 'plain', 'utf-8'))
        
        # Attach PDF
        with open(pdf_filename, "rb") as f:
            attach = MIMEApplication(f.read(), _subtype="pdf")
            attach.add_header(
                'Content-Disposition', 
                'attachment', 
                filename=f"Stock_Critique_{datetime.now().strftime('%d%m%Y')}.pdf"
            )
            msg.attach(attach)
        
        # Send to each recipient
        with smtplib.SMTP(EMAIL_CONFIG['smtp_server'], EMAIL_CONFIG['smtp_port']) as server:
            server.starttls()
            server.login(EMAIL_CONFIG['email'], EMAIL_CONFIG['password'])
            
            for email in recipients:
                msg['To'] = email
                server.sendmail(EMAIL_CONFIG['email'], email, msg.as_string())
                logger.info(f"Email envoyé à {email}")
                
    except Exception as e:
        logger.error(f"Erreur d'envoi d'email: {str(e)}")
    finally:
        # Clean up PDF file
        if os.path.exists(pdf_filename):
            os.remove(pdf_filename)

def check_low_stock():
    """Check for low stock items and notify users"""
    try:
        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor(dictionary=True)
        
        # Get items with quantity <= 2
        cursor.execute("""
            SELECT sous_categorie, quantite 
            FROM stock 
            WHERE quantite <= 2
            ORDER BY quantite ASC
        """)
        low_stock_items = cursor.fetchall()
        
        if low_stock_items:
            logger.warning(f"{len(low_stock_items)} éléments en stock critique détectés")
            
            # Generate PDF
            pdf_file = generate_pdf_report(low_stock_items)
            
            # Get all user emails and send notifications
            recipients = get_all_user_emails()
            if recipients:
                send_email_with_pdf(recipients, pdf_file)
            else:
                logger.error("Aucun email trouvé dans la table users")
        else:
            logger.info("Aucun stock critique détecté")
            
    except mysql.connector.Error as err:
        logger.error(f"Erreur de base de données: {err}")
    finally:
        if 'conn' in locals() and conn.is_connected():
            conn.close()

# ======================
# SCHEDULER SETUP
# ======================

def start_scheduler():
    """Configure and start the background scheduler"""
    scheduler = BackgroundScheduler()
    scheduler.add_job(
        check_low_stock,
        CronTrigger(
             hour=10,
             minute=0,        # Check every day at 10:00 AM
             second=0,
            timezone='UTC'  # Adjust to your timezone
        ),
        name='stock_check',
        max_instances=1
    )
    scheduler.start()
    return scheduler

# ======================
# MAIN EXECUTION
# ======================

if __name__ == '__main__':
    scheduler = None  # Define scheduler outside the try block
    try:
        logger.info("Démarrage du système de surveillance de stock...")
        scheduler = start_scheduler()
        
        # Keep the application running
        while True:
            pass
            
    except KeyboardInterrupt:
        logger.info("Arrêt du système...")
        if scheduler:
            scheduler.shutdown()
    except Exception as e:
        logger.error(f"Erreur critique: {str(e)}")