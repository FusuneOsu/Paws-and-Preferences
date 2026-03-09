<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Preferences</title>
    <link rel="icon" type="image/jpeg" href="images/tabIcon.jpg">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 100vw;
            height: 100vh;
            background: radial-gradient(ellipse at 30% 20%, #FFB3A0 0%, #FF9966 15%, #FF6B9D 30%, #D964A3 45%, #9D4EDD 55%, #7B2CBF 70%, #6A4C93 85%, #4A3F8F 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .container {
            position: relative;
            width: 400px;
            height: 500px;
            perspective: 1000px;
        }

        .instruction-text {
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 36px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            text-align: center;
            z-index: 10;
            pointer-events: none;
            white-space: nowrap;
        }

        .card-stack {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .empty-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 24px;
            text-align: center;
            display: none;
            z-index: 5;
            width: 100%;
        }

        .empty-message.show {
            display: block;
        }

        .back-home-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #004A8F;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .back-home-btn:hover {
            background: #003A72;
            transform: scale(1.05);
        }

        #buttons-container {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            z-index: 999;
            pointer-events: auto;
            display: flex;
            justify-content: center;
            width: 100%;
        }
    </style>
</head>
<body>
    <?php
        include 'swipe.php';
        include 'cardButtons.php';
        include 'yesNope.php';
        include 'catNames.php';
        include 'catSummary.php';
    ?>

    <button class="back-home-btn" onclick="window.location.href='index.php'">← Home</button>

    <div class="container">
        <div class="instruction-text">swipe left for nope, swipe right for like</div>
        <div class="card-stack" id="cardStack"></div>
        <div class="empty-message" id="emptyMessage">
            <!-- <p>No more cats! 🐱</p>
            <p style="font-size: 16px; margin-top: 20px;">
                <a href="index.php" style="color: white; text-decoration: underline;">Back to Home</a>
            </p> -->
        </div>
        <div id="buttons-container"></div>
    </div>

    <script>
        // Initialize the app using functions from likeDisilike.php
        async function initApp() {
            // Fetch cat names from API
            await fetchCatNames();

            // Initialize cat data (now preloads all images)
            await initializeCats();

            // Render initial cards
            renderCards();

            // Create and append floating buttons
            const buttonsContainer = document.getElementById('buttons-container');
            const floatingButtons = createFloatingButtons();
            buttonsContainer.appendChild(floatingButtons);
        }

        // Initialize on load
        window.addEventListener('load', initApp);
    </script>
</body>
</html>
