<!DOCTYPE html>
<html>

<head>
    <style>
        .card-buttons {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            z-index: 15;
        }

        .card-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .card-button:hover {
            transform: scale(1.1);
        }

        .card-button:active {
            transform: scale(0.95);
        }

        .like-btn {
            background: linear-gradient(45deg, #4CAF50, #66BB6A);
            color: white;
        }

        .nah-btn {
            background: linear-gradient(45deg, #f44336, #EF5350);
            color: white;
        }

        .rewind-btn {
            background: linear-gradient(45deg, #FF6B9D, #D964A3);
            color: white;
        }
    </style>
</head>

<body>
    <script>
        // floating button bar separate from the cards
        function createFloatingButtons() {
            const container = document.createElement('div');
            container.id = 'floating-buttons';
            container.style.cssText = 'display:flex;gap:20px;z-index:999;';

            // rewind button
            const rewindBtn = document.createElement('button');
            rewindBtn.className = 'card-button rewind-btn';
            rewindBtn.innerHTML = '↻';
            rewindBtn.title = 'Rewind last swipe';
            rewindBtn.onclick = () => rewindLastSwipe();

            // dislike button
            const nahBtn = document.createElement('button');
            nahBtn.className = 'card-button nah-btn';
            nahBtn.textContent = '✕';
            nahBtn.title = 'Dislike';
            nahBtn.onclick = () => triggerDislikeButton();

            // like button
            const likeBtn = document.createElement('button');
            likeBtn.className = 'card-button like-btn';
            likeBtn.textContent = '♥';
            likeBtn.title = 'Like';
            likeBtn.onclick = () => triggerLikeButton();

            container.appendChild(rewindBtn);
            container.appendChild(nahBtn);
            container.appendChild(likeBtn);
            return container;
        }

        function animateButtonSwipe(currentCard, direction) {
            const isLike = direction === 'like';
            currentCard.classList.remove('dragging-left', 'dragging-right', 'swiped-left', 'swiped-right');
            currentCard.classList.add(isLike ? 'dragging-right' : 'dragging-left');

            setTimeout(() => {
                currentCard.classList.remove('dragging-left', 'dragging-right');
                currentCard.classList.add(isLike ? 'liked' : 'disliked');
                currentCard.classList.add(isLike ? 'swiped-right' : 'swiped-left');
                currentCard.style.setProperty('--start-x', isLike ? '200px' : '-200px');
                currentCard.style.setProperty('--start-y', '-50px');
                currentCard.style.setProperty('--start-rotate', isLike ? '15deg' : '-15deg');
                currentCard.style.transition = 'none';
                currentCard.style.transform = '';

                const catData = cats[currentIndex];
                swipeHistory.push({
                    index: currentIndex,
                    action: direction,
                    dataUrl: catData?.dataUrl || null,
                    name: getCatName(currentIndex),
                    age: getRandomAge(currentIndex)
                });

                setTimeout(() => { currentIndex++; renderCards(); }, 600);
            }, 220);
        }

        function triggerLikeButton() {
            if (currentIndex >= cats.length) return;
            const currentCard = document.querySelector('.card');
            if (!currentCard) return;
            animateButtonSwipe(currentCard, 'like');
        }

        function triggerDislikeButton() {
            if (currentIndex >= cats.length) return;
            const currentCard = document.querySelector('.card');
            if (!currentCard) return;
            animateButtonSwipe(currentCard, 'dislike');
        }

        // expose on window
        window.createFloatingButtons = createFloatingButtons;
        window.triggerLikeButton = triggerLikeButton;
        window.triggerDislikeButton = triggerDislikeButton;
    </script>
</body>

</html>