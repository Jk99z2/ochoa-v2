<script>
document.getElementById("burger").addEventListener("click", function(e) {
  e.stopPropagation();
  document.getElementById("nav-menu").classList.toggle("open");
});
document.addEventListener("click", function() {
  document.getElementById("nav-menu").classList.remove("open");
});
</script>
