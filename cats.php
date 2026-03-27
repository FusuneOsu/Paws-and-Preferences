<!DOCTYPE html></html>
<html>

    <head>
        <style>
            .loading-indicator {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                display: none;
                align-items: center;
                gap: 12px;
                padding: 10px 16px;
                border-radius: 999px;
                background: rgba(0, 0, 0, 0.65);
                color: #fff;
                font-size: 14px;
                font-weight: 600;
                z-index: 3000;
                pointer-events: none;
            }

            .loading-indicator.show { display: flex; }

            .loading-spinner {
                width: 20px;
                height: 20px;
                border: 3px solid rgba(255, 255, 255, 0.35);
                border-top-color: #fff;
                border-radius: 50%;
                animation: spin 0.8s linear infinite;
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        </style>
    </head>

    <body>
        <script>
            let currentIndex = 0;
            let maxCats = 10;
            let cats = [];

            function getLoadingIndicator() {
                let indicator = document.getElementById('loadingIndicator');
                if (indicator) return indicator;

                indicator = document.createElement('div');
                indicator.id = 'loadingIndicator';
                indicator.className = 'loading-indicator';
                indicator.innerHTML = '<div class="loading-spinner"></div><span>Loading cats...</span>';
                document.body.appendChild(indicator);
                return indicator;
            }

            function showLoadingAnimation() {
                const indicator = getLoadingIndicator();
                indicator.classList.add('show');
            }

            function hideLoadingAnimation() {
                const indicator = document.getElementById('loadingIndicator');
                if (!indicator) return;
                indicator.classList.remove('show');
            }

            function generateCatUrl(index) {
                return `https://cataas.com/cat?width=300&height=400&cb=${index}`;
            }

            function initializeCats() {
                showLoadingAnimation();
                return new Promise((resolve) => {
                    const persistedCats = localStorage.getItem('catUrls');
                    if (persistedCats) {
                        const loadedCats = JSON.parse(persistedCats);
                        const loadPromises = loadedCats.map((catData, index) => {
                            return fetch(catData.url)
                                .then(response => response.blob())
                                .then(blob => {
                                    const blobUrl = URL.createObjectURL(blob);
                                    cats[index] = { url: catData.url, blobUrl: blobUrl };
                                })
                                .catch(err => {
                                    console.log(`Failed to preload cat ${index}:`, err);
                                    cats[index] = { url: catData.url, blobUrl: null };
                                });
                        });

                        Promise.all(loadPromises).then(() => {
                            hideLoadingAnimation();
                            resolve();
                        });
                    } else {
                        const loadPromises = [];
                        for (let i = 0; i < maxCats; i++) {
                            const url = generateCatUrl(i);
                            const loadPromise = fetch(url)
                                .then(response => response.blob())
                                .then(blob => {
                                    const blobUrl = URL.createObjectURL(blob);
                                    cats[i] = { url: url, blobUrl: blobUrl };
                                })
                                .catch(err => {
                                    console.log(`Failed to load cat ${i}:`, err);
                                    cats[i] = { url: url, blobUrl: null };
                                });
                            loadPromises.push(loadPromise);
                        }

                        Promise.all(loadPromises).then(() => {
                            const urlsToPersist = cats.map(cat => ({ url: cat.url }));
                            localStorage.setItem('catUrls', JSON.stringify(urlsToPersist));
                            hideLoadingAnimation();
                            resolve();
                        });
                    }
                });
            }

            window.initializeCats = initializeCats;
            window.generateCatUrl = generateCatUrl;
            window.showLoadingAnimation = showLoadingAnimation;
            window.hideLoadingAnimation = hideLoadingAnimation;
        </script>
    </body>
    
</html>