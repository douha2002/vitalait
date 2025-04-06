import mysql.connector
import requests
import sys
from datetime import datetime

def convert_to_serializable(obj):
    """Convert non-serializable objects to serializable formats"""
    if isinstance(obj, datetime):
        return obj.isoformat()
    elif hasattr(obj, '__dict__'):
        return obj.__dict__
    return obj

def main():
    try:
        # === DB CONNECTION CONFIG ===
        db = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="it_park",
            autocommit=True
        )
        cursor = db.cursor(dictionary=True)

        # === STEP 1: Get low stock items ===
        cursor.execute("SELECT * FROM stock WHERE quantite <= 2")
        low_stock_items = cursor.fetchall()

        if not low_stock_items:
            print("✅ No low-stock items found.")
            return

        # === STEP 2: Get ALL user emails ===
        cursor.execute("SELECT email FROM users")
        users = cursor.fetchall()
        
        if not users:
            print("⚠️ No users found in database")
            return
            
        emails = [user['email'] for user in users]

        # === STEP 3: Prepare serializable data ===
        serializable_items = []
        for item in low_stock_items:
            serializable_item = {}
            for key, value in item.items():
                serializable_item[key] = convert_to_serializable(value)
            serializable_items.append(serializable_item)

        # === STEP 4: Send alerts ===
        for email in emails:
            try:
                response = requests.post(
                    "http://127.0.0.1:8000/api/send-stock-alert",
                    headers={
                        "X-Secret-Key": "your-secret-key-here",
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    json={
                        "email": email,
                        "low_stock_items": serializable_items
                    },
                    timeout=10
                )
                
                if response.status_code == 200:
                    print(f"📨 Email sent to {email}")
                else:
                    print(f"❌ Failed to send to {email}: {response.text}")
                    
            except requests.exceptions.RequestException as e:
                print(f"⚠️ Network error sending to {email}: {str(e)}")

    except mysql.connector.Error as err:
        print(f"⚠️ Database error: {err}")
        sys.exit(1)
    finally:
        if 'db' in locals() and db.is_connected():
            cursor.close()
            db.close()

if __name__ == "__main__":
    main()