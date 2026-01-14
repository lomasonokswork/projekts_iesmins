const div = document.querySelector('html');
let isRotating = false;
let rotateLeft = true;

function rotate() {
  if (isRotating) return;
  isRotating = true;
  
  div.style.transition = "transform 1s ease-in-out";
  const direction = rotateLeft ? "-360deg" : "360deg";
  div.style.transform = `rotate(${direction})`;
  
  setTimeout(() => {
    div.style.transition = "none";
    div.style.transform = "rotate(0deg)";
    rotateLeft = !rotateLeft;
    isRotating = false;
  }, 1000);
}

function createFloatingShapes() {
  const cell2 = document.getElementById('cell2confetti');
  const colors = ['#fd0606', '#ffffff', '#ff0000', '#ff4800', '#00f947', '#ffcc00', '#b300ff', '#0687cc'];
  
  function createShape() {
    const shape = document.createElement('div');
    const color = colors[Math.floor(Math.random() * colors.length)];
    const size = Math.random() * 1 + 10;
    const startX = Math.random() * 500;
    const startY = -50;
    const duration = Math.random() * 2 + 6;
    const rotation = Math.random() * 360;
    
    shape.style.position = 'absolute';
    shape.style.width = size + 'px';
    shape.style.height = size + 'px';
    shape.style.backgroundColor = color;
    shape.style.left = startX + 'px';
    shape.style.top = startY + 'px';
    shape.style.opacity = '0.8';
    shape.style.transform = `rotate(${rotation}deg)`;
    shape.style.borderRadius = '4px';
    shape.style.boxShadow = `0 4px 8px rgba(0,0,0,0.2)`;
    shape.style.cursor = 'pointer';
    
    shape.animate([
      { 
        transform: `translateY(0px) rotate(${rotation}deg)`,
        opacity: '1'
      },
      { 
        transform: `translateY(${350}px) rotate(${rotation + 360}deg)`,
        opacity: '0.2'
      }
    ], {
      duration: duration * 1000,
      easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
      fill: 'forwards'
    });
    
    cell2.appendChild(shape);
    
    // laiks kad pazud
    setTimeout(() => shape.remove(), duration * 1000);
  }
  
  // atrums
  setInterval(createShape, 50);
}

window.addEventListener('DOMContentLoaded', createFloatingShapes);

