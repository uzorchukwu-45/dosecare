
  

  
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    
<style>


  /* 2. Wrapper to ensure no white gaps appear between sections */

  /* 3. Short, centered footer */
  .dose-footer {
    background-color: #1a2d42;
    color: white;
    padding: 10px 0; 
    text-align: center;
    width: 100%;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
  }
  #

  .footer-links {
    list-style: none;
    padding: 0;
    margin: 5px 0; 
    display: flex;
    justify-content: center;
    gap: 15px;
  }
  .footer-content{
    background-color: #1a2d42; /* Ensure the content area also has the blue background */
  }

  .footer-links li a {
    color: #cbd5e0;
    text-decoration: none;
    font-size: 0.8rem;
  }

  /* 4. This fixes the copyright line specifically */
  .copyright {
    font-size: 0.75rem;
    color: #a0aec0;
    background-color: #1a2d42; /* Explicitly sets the blue background */
    margin: 0;
    padding: 5px 0;
  }
</style>

<div class="page-wrapper">
  <div class="content-area">
      

  <footer class="dose-footer">
    <div class="footer-content">
      <span class="footer-logo" style="font-size: 1.5rem; font-weight: bold;">DoseCare</span>
      <p>Empowering medication adherence through smart technology.</p>
      
      <ul class="footer-links">
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Contact Support</a></li>
      </ul>

      <div class="copyright">
        &copy; <span id="current-year"></span> DoseCare Adherence Systems. All rights reserved.
      </div>
    </div>

    <script>
      document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
  </footer>
</div>





