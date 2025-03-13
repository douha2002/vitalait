<div class="search-container">
    <form method="GET" action="{{ route('search') }}" class="search-form">
        <input type="text" name="search" id="search" placeholder="Rechercher par Article,Quantité etc.." >
        <button type="submit"><i class="fas fa-search"></i></button>
   
    
    <!-- Reset Filter Button -->
    <a href="{{ route('equipments.index') }}" class="reset-filter-btn" title="Réinitialiser la recherche">
        <i class="fas fa-sync-alt"></i> 
    </a>
</form>
</div>

<style>
    .search-container {
        width: 100%;
        padding: 10px 0;
        display: flex;
        justify-content: center;
        gap: 10px; /* Space between search and reset button */
    }

    .search-form {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 600px;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .search-form input {
        flex: 1;
        border: none;
        padding: 8px 12px;
        font-size: 1rem;
        border-radius: 4px;
        outline: none;
    }

    .search-form button {
        background: white;
        color: black;
        border: none;
        padding: 8px 15px;
        margin-left: 5px;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .search-form button:hover {
        background: white;
    }

    .search-form button i {
        font-size: 1.2rem;
    }

    .reset-filter-btn {
        background: white;
        color: black;
        padding: 10px 15px;
        border-radius: 6px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 1rem;
        transition: background 0.3s;
    }

    .reset-filter-btn:hover {
        background: white;
    }

    .reset-filter-btn i {
        font-size: 1.2rem;
    }
</style>
