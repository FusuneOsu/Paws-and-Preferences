<!DOCTYPE html>
<html>
<head>
    <style>
        .summary-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            z-index: 2000;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
            padding: 20px;
        }

        .summary-overlay.show {
            display: flex;
        }

        .summary-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 30px;
            padding: 40px;
            max-width: 1200px;
            width: 50%;
            color: white;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .summary-title {
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .summary-count {
            font-size: 72px;
            font-weight: 900;
            color: #FFB3A0;
            margin: 20px 0;
            text-shadow: 0 3px 15px rgba(0, 0, 0, 0.4);
        }

        .summary-subtitle {
            font-size: 18px;
            opacity: 0.95;
            margin-bottom: 30px;
        }

        .liked-cats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
            max-height: 400px;
            overflow-y: scroll;
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: #667eea rgba(102, 126, 234, 0.1);
        }

        .liked-cat-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .liked-cat-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .liked-cat-image {
            width: 100%;
            height: 120px;
            object-fit: contain;
            background: rgba(255, 255, 255, 0.05);
        }

        .liked-cat-name {
            padding: 8px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .summary-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .summary-btn {
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .summary-btn-primary {
            background: #FFB3A0;
            color: #333;
        }

        .summary-btn-primary:hover {
            background: #FF9D88;
            transform: scale(1.05);
        }

        .summary-btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }

        .summary-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .no-likes-message {
            font-size: 20px;
            opacity: 0.8;
            margin: 20px 0;
        }

        /* Custom scrollbar styling matching website theme */
        .liked-cats-grid::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        .liked-cats-grid::-webkit-scrollbar-track {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 6px;
        }

        .liked-cats-grid::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 6px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .liked-cats-grid::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        .liked-cats-grid::-webkit-scrollbar-corner {
            background: rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body>
    <div id="summaryOverlay" class="summary-overlay">
        <div class="summary-container">
            <h1 class="summary-title">Session Summary</h1>
            <p class="summary-count" id="likeCount">0</p>
            <p class="summary-subtitle">Cats You Liked</p>
            
            <div class="liked-cats-grid" id="likedCatsGrid">
                <p class="no-likes-message">No cats liked yet</p>
            </div>

            <div class="summary-buttons">
                <button class="summary-btn summary-btn-primary" onclick="restartApp()">Find more cats</button>
                <button class="summary-btn summary-btn-secondary" onclick="goHome()">Home</button>
            </div>
        </div>
    </div>

    <script>
        function showSummary() {
            const likedCats = swipeHistory.filter(item => item.action === 'like');
            const likeCount = likedCats.length;

            // Update count
            document.getElementById('likeCount').textContent = likeCount;

            // Populate liked cats grid
            const grid = document.getElementById('likedCatsGrid');
            grid.innerHTML = '';

            if (likedCats.length === 0) {
                grid.innerHTML = '<p class="no-likes-message">No cats liked yet</p>';
            } else {
                likedCats.forEach((item, index) => {
                    const catCard = document.createElement('div');
                    catCard.className = 'liked-cat-card';
                    
                    const img = document.createElement('img');
                    // Use the captured data URL for guaranteed consistency
                    img.src = item.dataUrl || '';
                    img.alt = 'Liked cat';
                    img.className = 'liked-cat-image';
                    
                    const nameDiv = document.createElement('div');
                    nameDiv.className = 'liked-cat-name';
                    nameDiv.textContent = `${item.name}, ${item.age}y`;
                    
                    catCard.appendChild(img);
                    catCard.appendChild(nameDiv);
                    grid.appendChild(catCard);
                });
            }

            // Show overlay
            document.getElementById('summaryOverlay').classList.add('show');
        }

        async function restartApp() {
            // Reset global state
            currentIndex = 0;
            swipeHistory = [];
            
            // Hide summary
            document.getElementById('summaryOverlay').classList.remove('show');
            
            // Reinitialize cats and render (now waits for preloading)
            await initializeCats();
            renderCards();
        }

        function goHome() {
            window.location.href = 'index.php';
        }

        window.showSummary = showSummary;
        window.restartApp = restartApp;
        window.goHome = goHome;
    </script>
</body>
</html>
