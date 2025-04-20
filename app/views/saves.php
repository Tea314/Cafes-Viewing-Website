<?php
viewName('header', ['title' => 'Saves']);
?>

<main>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
            color: #EFE9D5;
            margin: 0;         }
        .development-section {
            min-height: 100vh;
            width: 100%;
            position: relative; 
            left: 0; 
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/mywebsite/public/assets/cafe-bg.jpg');
            background-size: cover;
            background-position: center;
            text-align: center;
            padding: 50px 20px;
            margin: 0; 
        }
        .development-content {
            background-color: rgba(39, 68, 93, 0.9); 
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 90%; 
        }
        .development-content h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #EFE9D5;
            margin-bottom: 20px;
        }
        .development-content p {
            font-size: 1.2rem;
            color: #EFE9D5;
            margin-bottom: 30px;
        }
        .coffee-icon {
            font-size: 4rem;
            color: #EFE9D5;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        .btn-home {
            background-color: #EFE9D5;
            color: #27445D;
            border: none;
            padding: 10px 20px;
            font-size: 1.1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn-home:hover {
            background-color: #d9d3c0; 
            color: #27445D;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        @media (max-width: 768px) {
            .development-content h1 {
                font-size: 2rem;
            }
            .development-content p {
                font-size: 1rem;
            }
            .coffee-icon {
                font-size: 3rem;
            }
            .development-content {
                padding: 30px;
            }
        }
    </style>
    <section class="development-section">
        <div class="development-content">
            <i class="fas fa-coffee coffee-icon"></i>
            <h1>Under Development</h1>
            <p>The "Saves" feature is brewing! Soon, you'll be able to view and manage your favorite cafés here. Stay tuned for updates!</p>
            <a href="/mywebsite/public/index.php" class="btn-home">Back to Homepage</a>
        </div>
    </section>
</main>
