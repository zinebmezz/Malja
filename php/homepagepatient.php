<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'db.php'; 

$sql = "SELECT tp.*, u.full_name, u.id AS user_id
        FROM therapist_profile tp
        JOIN therapists t ON tp.user_id = t.user_id
        JOIN users u ON u.id = t.user_id
        LIMIT 6";

$stmt = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Webpage Design</title>
    <link rel="stylesheet" href="../css/ex1.css">
</head>
<body>
  

  <div class="main">
    <div class="navbar">
      <div class="icon">
        <h2 class="logo">Maljā</h2>
      </div>

     <div class="menu">
  <ul class="nav-links">
    <li><a href="../php/homepagepatient.php">HOME</a></li>
    <li><a href="../html/about.html">ABOUT</a></li>
    <li><a href="#features">SERVICES</a></li>
    <li><a href="#contact">CONTACT</a></li>
    <br>
    <br>
    
  </ul>

  <div class="user-dropdown">
  <img src="../image/user.png" alt="Profile" id="profileBtn" class="profile-img" />
  <div class="dropdown-box" id="profileMenu">
    <a href="../php/patientdashboard.php">Dashboard</a>
    <a href="logout.php">Logout</a>
  </div>
</div>
</div>

        
      </div>
    </div>



  <div class="modal-overlay" id="login-modal">
    <div class="modal">
      <h3>Login</h3>
      <form>
        <input type="text" placeholder="Username" required>
        <input type="password" placeholder="Password" required>
        <button type="submit">Login</button>
      </form>
      <p>Don't have an account? <a href="#" id="signup-link">Sign Up</a></p>
      <p>Forgot your password? <a href="#" id="signup-link">Rest It</a></p>
    </div>
  </div>

  <div class="modal-overlay" id="signup-modal">
    <div class="modal">
      <h3>Sign Up</h3>
      <form>
        <input type="text" placeholder="Username" required>
        <input type="email" placeholder="Email" required>
        <input type="password" placeholder="Password" required>
        <input type="password" placeholder="Confirm Password" required>
    
        <div class="age-group">
          <p>Are you a:</p>
          <div class="radio-group">
            <label>
              <input type="radio" name="age-group" value="kid" required> Kid
            </label>
            <label>
              <input type="radio" name="age-group" value="teen" required> Teen
            </label>
            <label>
              <input type="radio" name="age-group" value="adult" required> Adult
            </label>
          </div>
        </div>
        <button type="submit">Sign Up</button>
      </form>
      <p>Already have an account? <a href="#" id="login-link">Login</a></p>
      <p>Are you a therapist? <a href="therapistsignup.html" id="therapist-link">Press here</a></p>
    </div>
  </div>

  <div class="modal-overlay" id="therapist-modal" style="display: none;">
    <div class="modal">
      <h3>Therapist Sign Up</h3>
  
      <a href="#" id="signup-back-link">Back</a>
    </div>
  </div>
        
        <div class="content">
            <h1> <br> <span> Welcome back </span>!
               
              <div class="desc"> Ready to continue your healing journey? </div></span> 
              </h1>
              <br>
            <p class="par"> Your next session is on <strong>May 20, 2025</strong> with <strong>Dr. Leila</strong> at 14:00.</p></p>
            
                </div>
                <?php if (isset($_GET['booked'])): ?>
  <p style="color: green; text-align:center;">✅ Booking request sent!</p>
<?php endif; ?>
                <br>
                <br>
                <br>
                <br>
                <br>
       <br>
       <div>
                <h2 class="searchfor"> Search for a therapist based on your needs!</h2>
                <br>
                <form action="../php/search.php" method="get" class="search" style="display: flex; align-items: center; gap: 10px;">
  <input class="srch" type="text" name="query" placeholder="Search by name, specialization, or issue..." style="flex: 1; padding: 10px; border-radius: 10px; border: 1px solid #ccc;">
  <button class="btn" type="submit" style="padding: 10px 20px; border-radius: 10px;">Search</button>
</form>
                </div>
              <br>
              <br>
              <br>
              <br>
              <br>

           
             <section style="display: flex; justify-content: center; margin: 30px 0;">
  <button 
    onclick="window.location.href='https://www.growcloser.com/chat/f6c73864-e444-4d99-9219-61269ec7f28b'" 
    style="
      background: linear-gradient(90deg, #eddddd 60%, #d1e7e0 100%);
      color: #19536b;
      font-weight: bold;
      font-size: 1.1rem;
      padding: 16px 32px;
      border: none;
      border-radius: 30px;
      box-shadow: 0 2px 10px rgba(25,83,107,0.08);
      cursor: pointer;
      transition: background 0.3s, color 0.3s, box-shadow 0.3s;
      display: flex;
      align-items: center;
      gap: 12px;
     
    "
    onmouseover="this.style.background='#d1e7e0'; this.style.color='#000';"
    onmouseout="this.style.background='linear-gradient(90deg, #eddddd 60%, #d1e7e0 100%)'; this.style.color='#19536b';"
  >
    <img src="../image/chat.png" alt="Chat Icon" style="width: 28px; height: 28px; vertical-align: middle;">
    Chat with Dr. Answers (Mental Health Chat)
  </button>
</section>
                   
      <div class="services-section">
  <h2 class="services-title">Our Services</h2>
  <div class="services-grid">

    <a href="../html/marriage.html" class="service-card">
      <img src="../image/talking.png" alt="Individual Counselling">
      <h4>Individual Counselling</h4>
    </a>

    <a href="../html/birth.html" class="service-card">
      <img src="../image/psychiatrist.png" alt="Psychiatric Consultation">
      <h4>Psychiatric Consultation</h4>
    </a>

    <a href="../html/Residence.html" class="service-card">
      <img src="../image/wedding-ring.png" alt="Couple Counselling">
      <h4>Couple Counselling</h4>
    </a>

    <a href="../html/death.html" class="service-card">
      <img src="../image/siblings.png" alt="Teen Therapy">
      <h4>Teen Therapy</h4>
    </a>

    <a href="../html/death.html" class="service-card">
      <img src="../image/children.png" alt="Kids Therapy">
      <h4>Kids Therapy</h4>
    </a>

  </div>
</div>
<br>
<br>
<br>
<br>
                    <section style="background-color: #eddddd; padding: 40px; border-radius: 15px; margin-top: 40px;">
  <h2 style="text-align: center; font-family: Verdana; font-size: 28px; color: #5a4637;">Your Journey with Maljā</h2>
  <div style="display: flex; justify-content: space-around; flex-wrap: wrap; margin-top: 30px;">
    
    <div style="flex: 1; min-width: 200px; max-width: 300px; text-align: center; padding: 20px;">
      <img src="../image/seeker.png" alt="Explore Therapists" width="80" height="80" style="margin-bottom: 15px;">
      <h4 style="color: #a07e5d;">Step 1: Explore Therapists</h4>
      <p style="color: #333;">Browse therapist profiles that match your needs and preferences.</p>
    </div>
    
    <div style="flex: 1; min-width: 200px; max-width: 300px; text-align: center; padding: 20px;">
      <img src="../image/schedule.png" alt="Book Session" width="80" height="80" style="margin-bottom: 15px;">
      <h4 style="color: #a07e5d;">Step 2: Book a Session</h4>
      <p style="color: #333;">Choose a date and time that works for you and send a booking request.</p>
    </div>

    <div style="flex: 1; min-width: 200px; max-width: 300px; text-align: center; padding: 20px;">
      <img src="../image/chat.png" alt="Chat" width="80" height="80" style="margin-bottom: 15px;">
      <h4 style="color: #a07e5d;">Step 3: Connect & Chat</h4>
      <p style="color: #333;">Communicate with your therapist directly in your secure messaging center.</p>
    </div>
    
    <div style="flex: 1; min-width: 200px; max-width: 300px; text-align: center; padding: 20px;">
      <img src="../image/progress.png" alt="Track Progress" width="80" height="80" style="margin-bottom: 15px;">
      <h4 style="color: #a07e5d;">Step 4: Track Progress</h4>
      <p style="color: #333;">View your past sessions, notes, and growth milestones in your dashboard.</p>
    </div>
    
  </div>
</section>
                          <br>
                          <br>
                          <br>
                          <br>
                          <br>
                          <br>
                          <br>
                         <div class="area">
  <h2 class="areatitle">Area Of Expertise</h2>
  <div class="expertise-grid">
    
    <div class="expertise-item">
      <img src="../image/anxiety.png" alt="Anxiety">
      <h3>Anxiety</h3>
    </div>

    <div class="expertise-item">
      <img src="../image/depression.png" alt="Depression">
      <h3>Depression</h3>
    </div>

    <div class="expertise-item">
      <img src="../image/kitchen.png" alt="OCD">
      <h3>OCD</h3>
    </div>

    <div class="expertise-item">
      <img src="../image/remember.png" alt="PTSD">
      <h3>PTSD</h3>
    </div>

    <div class="expertise-item">
      <img src="../image/drug-addict.png" alt="Addiction">
      <h3>Addiction</h3>
    </div>

    <div class="expertise-item">
      <img src="../image/interaction.png" alt="Relationships">
      <h3>Relationships</h3>
    </div>

    <div class="expertise-item">
      <img src="../image/fear.png" alt="Stress">
      <h3>Stress</h3>
    </div>
    </div>

 
</div>
                         <br>
                         <br>
                         <br>
                         <br>
                
                      <div class="rowrow-padded">  
                                  <div class="col-md-6">
                                    <div class="fh5co-food-menu to-animate-2" style="padding-right: 20px;">
                                      <h2 class="fh5co-drinks" style="padding-left: 39%; font-size: 30px; font-family: Verdana; padding-bottom: 30px;">Most Viewed Therapists</h2>
                                      <ul>
                                <?php while ($row = $stmt->fetch()): ?>
  <li>
    <div class="fh5co-food-desc">
     <?php
$defaultImage = '../image/default.png';
$imagePath = !empty($row['profile_picture']) 
    ? '../uploads/' . $row['profile_picture'] 
    : $defaultImage;
?>

<img src="<?= htmlspecialchars($imagePath) ?>" 
     class="img-responsive therapist-img" 
     alt="<?= htmlspecialchars($row['full_name']) ?>">
      <h3><?php echo htmlspecialchars($row['full_name']); ?></h3>
      <p><?php echo htmlspecialchars($row['specialization']); ?> | <?php echo $row['experience']; ?> years experience.</p>
    </div>
    <div class="fh5co-food-pricing">
      “<?php echo htmlspecialchars($row['bio']); ?>”
      <br>
      <a href="../php/viewprofile.php?id=<?= $row['user_id'] ?>" class="view-profile-btn">View Profile</a>
    </div>
  </li>
<?php endwhile; ?>
                                    </ul>
                                  </div>
                                </div>
                   
                 
        </div>
        <br>
        <br>
        <br>
        <br>
        <section class="testimonials">
  <h2>Voices from Our Community</h2>
  <div class="testimonial-container">
    <div class="testimonial-card">
      <p class="quote">“Maljā helped me open up and finally get the help I needed.”</p>
      <p class="user">– Maria</p>
    </div>
    <div class="testimonial-card">
      <p class="quote">“I felt safe and heard. It’s everything I needed.”</p>
      <p class="user">– Amine</p>
    </div>
    <div class="testimonial-card">
      <p class="quote">“I never thought online therapy could feel so personal. Thank you!”</p>
      <p class="user">– Samir</p>
    </div>
  </div>
</section>
 <br>
<br> 
<br>
<br>
<section class="cta-section">

<h2>How can we support you today, <?= htmlspecialchars($_SESSION['full_name']) ?>?</h2>
  <div class="cta-buttons">
    <a href="book.php" class="btn-cta">Book a Session</a>
    <a href="therapists.php" class="btn-cta">Explore Therapists</a>
    <a href="../php/message.php" class="btn-cta">My Messages</a>
  </div>
</section>
<br>
<br>
<section class="quick-access">
  <h2 class="section-title">Quick Access</h2>
  <div class="quick-cards">
    <a href="../html/book.html" class="quick-card">
      <img src="../image/schedule.png" alt="Book Session">
      <p>View Upcoming Sessions</p>
    </a>
    <a href="../html/therapists.html" class="quick-card">
      <img src="../image/therapist.png" alt="Meet Therapists">
      <p>Meet Therapists</p>
    </a>
    <a href="../html/pricing.html" class="quick-card">
      <img src="../image/cashless-payment.png" alt="Pricing">
      <p>View Pricing</p>
    </a>
  </div>
</section>
  <br>
  <br>
  <br>
  <br>
  <section style="margin-top: 50px; padding: 30px; background-color:#eddddd; border-radius: 15px;">
  <h2 style="text-align: center; font-family: Verdana; font-size: 28px; color: #5a4637;">Frequently Asked Questions</h2>
  <div style="max-width: 800px; margin: 30px auto;">
    <p><strong style="color: #a07e5d;">Q: Is therapy confidential?</strong></p>
    <p style="margin-bottom: 20px;">A: Absolutely. All sessions are encrypted and private between you and your therapist.</p>
    
    <p><strong style="color: #a07e5d;">Q: How do I pay for sessions?</strong></p>
    <p style="margin-bottom: 20px;">A: After your therapist confirms a booking, you'll receive secure payment options.</p>

    <p><strong style="color: #a07e5d;">Q: Can I choose a therapist based on language or gender?</strong></p>
    <p style="margin-bottom: 20px;">A: Yes, our search filters help you find therapists that match your preferences.</p>
  </div>
</section>

  </div>
<br>
        <br>
        <br>
        <br><br>
        <br>
        <br>
        <br>
   
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
    <footer class="civil-state-footer">
        <div class="container">
          <div class="footer-content">
           
            <div class="footer-logo">
              <h3>Maljā </h3>

            </div>
      
           
            <div class="footer-menu">
              <h4>Quick Links</h4>
              <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
              </ul>
            </div>
      
          
            <div id="contact" class="footer-contact">
              <h4>Contact Us</h4>
              <p>Email: info@Malja.com</p>
              <p>Phone: +123 456 7890</p>
              <p>Address: Annaba, Annaba, Algeria</p>
            </div>
          </div>
      
      </footer>
 
        <script>
  const profileBtn = document.getElementById('profileBtn');
  const profileMenu = document.getElementById('profileMenu');

  profileBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    profileMenu.classList.toggle('show');
  });

  window.addEventListener('click', () => {
    profileMenu.classList.remove('show');
  });

  profileMenu.addEventListener('click', (e) => {
    e.stopPropagation();
  });
</script>
   
<script>
  const icon = document.getElementById("notificationIcon");
  const box = document.getElementById("notificationBox");

  icon.addEventListener("click", () => {
    box.classList.toggle("show");
  });

  document.addEventListener("click", function (e) {
    if (!icon.contains(e.target) && !box.contains(e.target)) {
      box.classList.remove("show");
    }
  });
</script>

      <script>
        const loginBtn = document.getElementById('login-btn');
        const loginModal = document.getElementById('login-modal');
        const signupBtn = document.getElementById('signup-btn');
        const signupModal = document.getElementById('signup-modal');
        const signupLink = document.getElementById('signup-link');
        const loginLink = document.getElementById('login-link');
        const therapistLink = document.getElementById('therapist-link');
        const therapistModal = document.getElementById('therapist-modal');
        const signupBackLink = document.getElementById('signup-back-link');
    
        loginBtn.addEventListener('click', (e) => {
          e.preventDefault();
          loginModal.classList.add('active');
        });
    
        signupBtn.addEventListener('click', (e) => {
          e.preventDefault();
          signupModal.classList.add('active');
        });
    
        signupLink.addEventListener('click', (e) => {
          e.preventDefault();
          loginModal.classList.remove('active');
          signupModal.classList.add('active');
        });
    
        loginLink.addEventListener('click', (e) => {
          e.preventDefault();
          signupModal.classList.remove('active');
          loginModal.classList.add('active');
        });
    
        [loginModal, signupModal, therapistModal].forEach(modal => {
          if (modal) {
            modal.addEventListener('click', (e) => {
              if (e.target === modal) {
                modal.classList.remove('active');
              }
            });
          }
        });
      </script>


</body>
</html>

