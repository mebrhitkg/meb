// Minimal JS for interactions
document.addEventListener('DOMContentLoaded', ()=>{
  // dark mode based on localStorage
  const dark = localStorage.getItem('dark') === '1'
  if(dark) document.body.classList.add('dark')
});
