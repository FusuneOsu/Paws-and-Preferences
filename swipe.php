<!DOCTYPE html>
<html>
    
<head>
    <style>
        /* swipe animations and card styling */
        .card {
            position: absolute;
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            cursor: grab;
            user-select: none;
            transition: transform 0.3s ease-out, box-shadow 0.1s ease-out;
        }

        .card:active { cursor: grabbing; }

        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card.swiped-right { animation: swipeRight 0.6s ease-out forwards; z-index: 200; }
        .card.swiped-left  { animation: swipeLeft 0.6s ease-out forwards; z-index: 200; }

        @keyframes swipeRight {
            0% { transform: translateX(var(--start-x,0)) translateY(var(--start-y,0)) rotate(var(--start-rotate,0deg)); opacity:1; }
            100% { transform: translateX(calc(var(--start-x,0)+500px)) translateY(calc(var(--start-y,0)-100px)) rotate(calc(var(--start-rotate,0deg)+30deg)); opacity:0; }
        }

        @keyframes swipeLeft {
            0% { transform: translateX(var(--start-x,0)) translateY(var(--start-y,0)) rotate(var(--start-rotate,0deg)); opacity:1; }
            100% { transform: translateX(calc(var(--start-x,0)-500px)) translateY(calc(var(--start-y,0)-100px)) rotate(calc(var(--start-rotate,0deg)-30deg)); opacity:0; }
        }

        .rewind-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px 25px;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            z-index: 1000;
            animation: fadeInOut 2s ease-in-out forwards;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
            20% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            80% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
        }
    </style>
    <?php include 'cats.php'; ?>
</head>

<body>
    <script>
        let swipeHistory = [];

        function addSwipeListeners(card) {
            let startX=0, startY=0, isDragging=false;
            const handleStart=(e)=>{
                e.preventDefault();
                startX = e.type.includes('mouse')?e.clientX:e.touches[0].clientX;
                startY = e.type.includes('mouse')?e.clientY:e.touches[0].clientY;
                isDragging=true; card.style.transition='none';
            };
            const handleMove=(e)=>{
                if(!isDragging) return;
                const currentX = e.type.includes('mouse')?e.clientX:e.touches[0].clientX;
                const currentY = e.type.includes('mouse')?e.clientY:e.touches[0].clientY;
                const diffX=currentX-startX, diffY=currentY-startY;
                const angle=(diffX/100)*15;
                card.style.transform=`translateX(${diffX}px) translateY(${diffY}px) rotate(${angle}deg)`;
                if(diffX>50){ card.classList.remove('dragging-left'); card.classList.add('dragging-right'); }
                else if(diffX<-50){ card.classList.remove('dragging-right'); card.classList.add('dragging-left'); }
                else card.classList.remove('dragging-right','dragging-left');
            };
            const handleEnd=(e)=>{
                if(!isDragging) return; isDragging=false;
                const currentX = e.type.includes('mouse')?e.clientX:e.changedTouches[0].clientX;
                const currentY = e.type.includes('mouse')?e.clientY:e.changedTouches[0].clientY;
                const diffX=currentX-startX, diffY=currentY-startY;
                const angle=(diffX/100)*15;
                card.style.transition=''; card.classList.remove('dragging-right','dragging-left');
                if(diffX>100){
                    card.classList.add('liked');
                    card.style.setProperty('--start-x',diffX+'px');
                    card.style.setProperty('--start-y',diffY+'px');
                    card.style.setProperty('--start-rotate',angle+'deg');
                    card.style.transition = 'none'; 
                    card.style.transform=''; 
                    card.classList.add('swiped-right');
                    const catData = cats[currentIndex];
                    swipeHistory.push({
                        index: currentIndex,
                        action: 'like',
                        dataUrl: catData?.dataUrl || null,
                        name: getCatName(currentIndex),
                        age: getRandomAge(currentIndex)
                    });
                    setTimeout(()=>{currentIndex++; renderCards();},600);
                } else if(diffX<-100){
                    card.classList.add('disliked');
                    card.style.setProperty('--start-x',diffX+'px');
                    card.style.setProperty('--start-y',diffY+'px');
                    card.style.setProperty('--start-rotate',angle+'deg');
                    card.style.transition = 'none'; 
                    card.style.transform=''; 
                    card.classList.add('swiped-left');
                    const catData = cats[currentIndex];
                    swipeHistory.push({
                        index: currentIndex,
                        action: 'dislike',
                        dataUrl: catData?.dataUrl || null,
                        name: getCatName(currentIndex),
                        age: getRandomAge(currentIndex)
                    });
                    setTimeout(()=>{currentIndex++; renderCards();},600);
                } else {
                    card.style.transform='translateX(0) translateY(0) rotate(0deg)';
                }
            };
            card.addEventListener('mousedown',handleStart);
            document.addEventListener('mousemove',handleMove);
            document.addEventListener('mouseup',handleEnd);
            card.addEventListener('touchstart',handleStart);
            document.addEventListener('touchmove',handleMove);
            document.addEventListener('touchend',handleEnd);
        }

        function rewindLastSwipe(){
            if(swipeHistory.length===0){alert('No swipes to rewind!');return;}
            const last=swipeHistory.pop(); currentIndex=last.index;
            showRewindMessage(); renderCards();
        }

        function showRewindMessage(){
            const message=document.createElement('div');
            message.className='rewind-message';
            message.textContent='↻ Rewound!';
            document.body.appendChild(message);
            setTimeout(()=>{document.body.removeChild(message);},2000);
        }

        function renderCards(){
            const cardStack=document.getElementById('cardStack'); cardStack.innerHTML='';
            for(let i=0;i<3 && currentIndex+i<cats.length;i++){
                const catData = cats[currentIndex+i];
                // Use blob URL if available, otherwise use original URL
                const cardUrl = catData.blobUrl || catData.url;
                const card=createCard(cardUrl,i);
                cardStack.appendChild(card);
                if(i===0) addSwipeListeners(card);
            }
            const isEmpty = currentIndex>=cats.length;
            document.getElementById('emptyMessage').classList.toggle('show', isEmpty);
            if(isEmpty && typeof showSummary==='function') {
                setTimeout(()=>{showSummary();}, 500);
            }
        }

        function createCard(catUrl,index){
            const card=document.createElement('div');card.className='card';card.style.zIndex=100-index;
            if(index===0){card.style.transform=`scale(0.95) translateY(20px)`;setTimeout(()=>{card.style.transform=`scale(1) translateY(0)`;},10);}else{card.style.transform=`scale(${1-index*0.05}) translateY(${index*20}px)`;}
            const img=document.createElement('img');img.src=catUrl;img.alt='Cat image';img.draggable=false;img.style.pointerEvents='none';img.oncontextmenu=e=>e.preventDefault();
            img.crossOrigin = 'anonymous';
            img.onload=()=>{
                card.appendChild(img);
                if(typeof addYesNoOverlays==='function') addYesNoOverlays(card);
                if(typeof addCatInfo==='function') addCatInfo(card, currentIndex+index);
                
                // Capture the actual displayed image as a data URL for the summary
                const catIdx = currentIndex + index;
                if (catIdx < cats.length && !cats[catIdx].dataUrl) {
                    try {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.naturalWidth;
                        canvas.height = img.naturalHeight;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        cats[catIdx].dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                    } catch(e) {
                        console.log('Canvas capture failed:', e);
                    }
                }
            };
            img.onerror=()=>{currentIndex++; renderCards();};
            return card;
        }

        window.addSwipeListeners=addSwipeListeners;
        window.rewindLastSwipe=rewindLastSwipe;
        window.swipeHistory=swipeHistory;
        window.renderCards=renderCards;
        window.createCard=createCard;
        window.showRewindMessage=showRewindMessage;
    </script>
</body>

</html>
