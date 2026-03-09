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
            min-height: 100vh;
            background: radial-gradient(ellipse at 30% 20%, #FFB3A0 0%, #FF9966 15%, #FF6B9D 30%, #D964A3 45%, #9D4EDD 55%, #7B2CBF 70%, #6A4C93 85%, #4A3F8F 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 32px 20px;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .container {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 28px;
            width: min(98vw, 1480px);
        }

        .title {
            font-size: clamp(44px, 6vw, 72px);
            font-weight: bold;
            color: black;
            text-shadow: -2px -2px 0 white, 2px -2px 0 white, -2px 2px 0 white, 2px 2px 0 white,
                         -2px 0 0 white, 2px 0 0 white, 0 -2px 0 white, 0 2px 0 white;
            width: auto;
            line-height: 1.15;
            white-space: nowrap;
        }

        .slider-panel {
            width: min(92vw, 560px);
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 28px;
            padding: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 18px 40px rgba(37, 16, 72, 0.28);
        }

        .slider-frame {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(135deg, #6ec7d9 0%, #91d46b 45%, #9f4fd1 100%);
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.04);
            transition: background 0.35s ease;
        }

        .slider-frame.slide-like {
            background: linear-gradient(135deg, #6ec7d9 0%, #8fcb66 42%, #8e39d2 100%);
        }

        .slider-frame.slide-nope {
            background: linear-gradient(135deg, #f4aa63 0%, #f06f83 46%, #8e38d1 100%);
        }

        .slider-frame.slide-summary {
            background: linear-gradient(135deg, #6679e8 0%, #7a5bc7 52%, #a35bd5 100%);
        }

        .slider-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            background: transparent;
        }

        .slider-caption {
            margin-top: 16px;
            font-size: 26px;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35);
            min-height: 34px;
        }

        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 48px;
            height: 48px;
            border: none;
            border-radius: 50%;
            background: rgba(18, 28, 46, 0.62);
            color: white;
            font-size: 28px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .slider-arrow:hover {
            background: rgba(18, 28, 46, 0.82);
        }

        .slider-arrow-left {
            left: 14px;
        }

        .slider-arrow-right {
            right: 14px;
        }

        .slider-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 16px;
        }

        .slider-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.35);
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .slider-dot.active {
            background: white;
            transform: scale(1.2);
        }

        .start-button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .start-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        @media (max-width: 640px) {
            .container {
                gap: 22px;
            }

            .title {
                white-space: normal;
            }

            .slider-panel {
                padding: 14px;
            }

            .slider-caption {
                font-size: 20px;
            }

            .slider-arrow {
                width: 40px;
                height: 40px;
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Paws & Preferences: Find Your Favourite Kitty</div>
        <div class="slider-panel">
            <div class="slider-frame">
                <button id="prevSlide" class="slider-arrow slider-arrow-left" type="button" aria-label="Previous slide">&#8249;</button>
                <img id="featureImage" class="slider-image" src="images/like.png" alt="Feature preview">
                <button id="nextSlide" class="slider-arrow slider-arrow-right" type="button" aria-label="Next slide">&#8250;</button>
            </div>
            <div id="featureCaption" class="slider-caption">swipe right for like</div>
            <div class="slider-dots" aria-hidden="true">
                <span class="slider-dot active"></span>
                <span class="slider-dot"></span>
                <span class="slider-dot"></span>
            </div>
        </div>
        <button class="start-button" onclick="window.location.href='app.php'">start →</button>
    </div>

    <script>
        const slides = [
            {
                image: 'images/like.png',
                text: 'swipe right for like',
                frameClass: 'slide-like'
            },
            {
                image: 'images/nope.png',
                text: 'swipe left for nope',
                frameClass: 'slide-nope'
            },
            {
                image: 'images/summary.png',
                text: 'view a summary of your liked cats',
                frameClass: 'slide-summary'
            }
        ];

        let activeSlideIndex = 0;
        let slideIntervalId;

        function renderSlide(index) {
            const image = document.getElementById('featureImage');
            const caption = document.getElementById('featureCaption');
            const dots = document.querySelectorAll('.slider-dot');
            const frame = document.querySelector('.slider-frame');

            image.src = slides[index].image;
            caption.textContent = slides[index].text;
            frame.classList.remove('slide-like', 'slide-nope', 'slide-summary');
            frame.classList.add(slides[index].frameClass);

            dots.forEach((dot, dotIndex) => {
                dot.classList.toggle('active', dotIndex === index);
            });
        }

        function moveSlide(direction) {
            activeSlideIndex = (activeSlideIndex + direction + slides.length) % slides.length;
            renderSlide(activeSlideIndex);
        }

        function startSlideShow() {
            clearInterval(slideIntervalId);
            slideIntervalId = setInterval(() => {
                moveSlide(1);
            }, 3000);
        }

        document.getElementById('prevSlide').addEventListener('click', () => {
            moveSlide(-1);
            startSlideShow();
        });

        document.getElementById('nextSlide').addEventListener('click', () => {
            moveSlide(1);
            startSlideShow();
        });

        startSlideShow();
        renderSlide(activeSlideIndex);
        startSlideShow();
    </script>
</body>
</html>