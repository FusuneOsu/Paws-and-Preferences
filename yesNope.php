<!DOCTYPE html>
<html>

<head>
    <style>
        /* yes/no overlay and feedback styles */
        .like-overlay, .dislike-overlay {
            position: absolute;
            font-size: clamp(56px, 20vw, 96px);
            font-weight: 900;
            z-index: 20;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            letter-spacing: 2px;
        }

        .like-overlay {
            color: #4CAF50;
            top: 30px;
            left: 20px;
            transform: rotate(-25deg);
            text-shadow: 
                -3px -3px 0 rgba(0,0,0,0.8),
                3px -3px 0 rgba(0,0,0,0.8),
                -3px 3px 0 rgba(0,0,0,0.8),
                3px 3px 0 rgba(0,0,0,0.8),
                0 0 20px rgba(76, 175, 80, 0.8),
                0 0 30px rgba(76, 175, 80, 0.6);
        }

        .dislike-overlay {
            color: #f44336;
            top: 20px;
            right: 20px;
            transform: rotate(25deg);
            text-shadow: 
                -3px -3px 0 rgba(0,0,0,0.8),
                3px -3px 0 rgba(0,0,0,0.8),
                -3px 3px 0 rgba(0,0,0,0.8),
                3px 3px 0 rgba(0,0,0,0.8),
                0 0 20px rgba(244, 67, 54, 0.8),
                0 0 30px rgba(244, 67, 54, 0.6);
        }


        .card.dragging-right .like-overlay { opacity: 1; }
        .card.dragging-left .dislike-overlay { opacity: 1; }
    </style>
</head>

<body>
    <script>
        // append overlays to a card
        function addYesNoOverlays(card) {
            const likeOverlay = document.createElement('div');
            likeOverlay.className = 'like-overlay';
            likeOverlay.textContent = 'LIKE';

            const dislikeOverlay = document.createElement('div');
            dislikeOverlay.className = 'dislike-overlay';
            dislikeOverlay.textContent = 'NOPE';

            card.appendChild(likeOverlay);
            card.appendChild(dislikeOverlay);
        }

        window.addYesNoOverlays = addYesNoOverlays;
    </script>
</body>
</html>