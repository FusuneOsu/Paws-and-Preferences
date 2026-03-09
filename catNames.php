<!DOCTYPE html>
<html>
<head>
    <style>
        .cat-info {
            position: absolute;
            bottom: 80px;
            left: 0;
            right: 0;
            padding: 20px;
            color: white;
            z-index: 10;
        }

        .cat-name {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
        }

        .cat-age {
            font-size: 16px;
            margin: 0;
            opacity: 0.9;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <script>
        let catNames = [];
        let catAges = {};

        async function fetchCatNames() {
            try {
                const response = await fetch('https://tools.estevecastells.com/api/cats/v1?limit=50');
                const names = await response.json();
                catNames = names;
                return names;
            } catch (error) {
                console.error('Error fetching cat names:', error);
                catNames = Array.from({ length: 20 }, (_, i) => `Whiskers ${i + 1}`);
                return catNames;
            }
        }

        function getCatName(index) {
            if (catNames.length === 0) {
                return `Whiskers ${index + 1}`;
            }
            return catNames[index % catNames.length];
        }

        function getRandomAge(catIndex) {
            if (catAges[catIndex] === undefined) {
                catAges[catIndex] = Math.floor(Math.random() * 15) + 1;
            }
            return catAges[catIndex];
        }

        function addCatInfo(card, catIndex) {
            const infoDiv = document.createElement('div');
            infoDiv.className = 'cat-info';
            
            const nameP = document.createElement('p');
            nameP.className = 'cat-name';
            nameP.textContent = getCatName(catIndex);
            
            const ageP = document.createElement('p');
            ageP.className = 'cat-age';
            ageP.textContent = `${getRandomAge(catIndex)} years old`;
            
            infoDiv.appendChild(nameP);
            infoDiv.appendChild(ageP);
            card.appendChild(infoDiv);
        }

        // Expose on window
        window.fetchCatNames = fetchCatNames;
        window.getCatName = getCatName;
        window.addCatInfo = addCatInfo;
        window.getRandomAge = getRandomAge;
    </script>
</body>
</html>
