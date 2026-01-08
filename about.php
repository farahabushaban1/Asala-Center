<?php 
// Include required files
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'includes/functions.php';

$currentLang = getCurrentLanguage();
$lang = loadLanguage($currentLang);

include 'includes/header.php'; 
?>

<!-- Hero Section with Background Image -->
<section class="about-hero-section">
    <div class="about-hero-content">
        <h6 class="section-subtitle"><?= $lang['about_us'] ?></h6>
        <h2 class="section-title about-page-title">
            <?php 
            $titleText = $lang['about_us_asala_center'];
            $words = explode(' ', $titleText);
            foreach ($words as $index => $word) {
                echo '<span class="title-word">' . htmlspecialchars($word) . '</span>';
                if ($index < count($words) - 1) {
                    echo '<span class="title-space"> </span>';
                }
            }
            ?>
        </h2>
        <p class="section-description"><?= $lang['about_journey_description'] ?></p>
    </div>
</section>

<div class="container py-5" style="margin-top: 50px;">
             <!-- Main Content Section -->
    <div class="row align-items-start mb-5">
        <!-- Left Column - Text Content -->
        <div class="col-lg-6">
            <div class="about-content">
                                <h3 class="about-story-title"><?= $lang['our_story'] ?></h3>
                <div class="about-story-text">
                                         <p class="mb-4">
                         <?= $lang['about_beginning'] ?>
                     </p>
                     <p class="mb-4">
                         <?= $lang['about_aim'] ?>
                     </p>
                     <p class="mb-4">
                         <?= $lang['about_vision'] ?>
                     </p>
                </div>
                
                
            </div>
        </div>
        
                <!-- Right Column - Images -->
        <div class="col-lg-6">
            <div class="about-images">
                <div class="image-layout">
                    <div class="left-column">
                        <div class="main-image">
                            <img src="assets/images/about-model1.jpeg" alt="Palestinian Model 1" class="about-model-image">
                        </div>
                    </div>
                    <div class="right-column">
                        <div class="main-image">
                            <img src="assets/images/about-model4.jpeg" alt="Palestinian Model 4" class="about-model-image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Values Section -->
    <div class="row">
        <div class="col-12 text-center mb-5">
                         <h3 class="values-section-title"><?= $lang['our_values'] ?></h3>
        </div>
    </div>
    
    <!-- Values Grid -->
    <div class="row mb-5">
        <!-- Column 1 -->
        <div class="col-lg-6">
            <div class="values-column">
                <div class="value-card mb-4">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                                         <h4 class="value-title"><?= $lang['passion_for_heritage'] ?></h4>
                     <p class="value-description"><?= $lang['passion_heritage_desc'] ?></p>
                 </div>
                 <div class="value-card mb-4">
                     <div class="value-icon">
                         <i class="fas fa-users"></i>
                     </div>
                     <h4 class="value-title"><?= $lang['community_support'] ?></h4>
                     <p class="value-description"><?= $lang['community_support_desc'] ?></p>
                 </div>
                 <div class="value-card mb-4">
                     <div class="value-icon">
                         <i class="fas fa-comments"></i>
                     </div>
                     <h4 class="value-title"><?= $lang['our_message'] ?></h4>
                     <p class="value-description"><?= $lang['our_message_desc'] ?></p>
                </div>

            
            </div>
        </div>
        
        <!-- Column 2 -->
        <div class="col-lg-6">
            <div class="values-column">
                <div class="value-card mb-4">
                    <div class="value-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                                         <h4 class="value-title"><?= $lang['high_quality'] ?></h4>
                     <p class="value-description"><?= $lang['high_quality_desc'] ?></p>
                 </div>
                 <div class="value-card mb-4">
                     <div class="value-icon">
                         <i class="fas fa-lightbulb"></i>
                     </div>
                     <h4 class="value-title"><?= $lang['authenticity_creativity'] ?></h4>
                     <p class="value-description"><?= $lang['authenticity_creativity_desc'] ?></p>
                 </div>
                 <div class="value-card mb-4">
                     <div class="value-icon">
                         <i class="fas fa-eye"></i>
                     </div>
                     <h4 class="value-title"><?= $lang['our_vision'] ?></h4>
                     <p class="value-description"><?= $lang['our_vision_desc'] ?></p>
                </div>
                
            </div>
        </div>
    </div>
</div>

<style>
/* About Hero Section with Background Image */
.about-hero-section {
    position: relative;
    background-image: url('assets/images/about-bak.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 100px 20px 80px 20px;
    margin-top: 80px;
    overflow: hidden;
}

.about-hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.53);
    z-index: 0;
}

.about-hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
    max-width: 900px;
    width: 100%;
}

/* About Page Styles */
.section-description {
    color: var(--text-dark);
    font-size: 1.1rem;
    margin-top: 1rem;
    line-height: 1.6;
}

/* Animated Title Styles */
.about-page-title {
    position: relative;
    display: inline-block;
    padding-bottom: 20px;
}

.about-page-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    animation: underlineExpand 1.2s ease-out 1s forwards;
    border-radius: 2px;
}

@keyframes underlineExpand {
    0% {
        width: 0;
        opacity: 0;
    }
    100% {
        width: 60%;
        opacity: 1;
    }
}

.about-page-title .title-word {
    display: inline-block;
    position: relative;
    animation: wordFadeIn 0.6s ease-out backwards;
}

.about-page-title .title-word:nth-child(1) {
    animation-delay: 0.1s;
}

.about-page-title .title-word:nth-child(2) {
    animation-delay: 0.25s;
}

.about-page-title .title-word:nth-child(3) {
    animation-delay: 0.4s;
}

.about-page-title .title-word:nth-child(4) {
    animation-delay: 0.55s;
}

.about-page-title .title-word:nth-child(5) {
    animation-delay: 0.7s;
}

.about-page-title .title-word:nth-child(6) {
    animation-delay: 0.85s;
}

.about-page-title .title-space {
    display: inline-block;
    width: 0.3em;
}

@keyframes wordFadeIn {
    0% {
        opacity: 0;
        transform: translateY(25px) scale(0.9);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Simple hover effect - no animation, just color change */
.about-page-title:hover {
    color: var(--primary-color);
    transition: color 0.3s ease;
}

.about-story-title {
    color: var(--primary-color);
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 1.5rem;
}

.about-story-text p {
    color: var(--text-dark);
    font-size: 0.85rem;
    line-height: 1.3;
    margin-bottom: 0.3rem;
}

/* Image Layout Styles */
.about-images {
    padding: 0;
    margin-top: 0;
}

.image-layout {
    position: relative;
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    justify-content: center;
    gap: 1rem;
    height: 100%;
}

.left-column {
    flex: 1;
    display: flex;
    align-items: flex-start;
    justify-content: center;
}

.right-column {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.main-image {
    width: 100%;
    max-width: 100%;
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.about-model-image {
    width: 100%;
    height: auto;
    display: block;
    border: none;
    border-radius: 10px;
    transition: transform 0.3s ease;
}

.about-model-image:hover {
    transform: scale(1.05);
}

/* Values Section Styles */
.values-section {
    margin-top: 3rem;
}

.values-title {
    color: var(--text-dark);
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.values-list {
    list-style: none;
    padding: 0;
}

.values-list li {
    color: var(--text-dark);
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 0.8rem;
    padding-left: 0;
    position: relative;
}

.values-list li strong {
    color: var(--text-dark);
}

.values-section-title {
    color: var(--primary-color);
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 3rem;
}

.values-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.value-card {
    background: var(--white);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    text-align: center;
}

.value-card:hover {
    transform: translateY(-10px);
}

.value-icon {
    width: 60px;
    height: 60px;
    background: rgba(165, 30, 63, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem auto;
    box-shadow: 0 4px 15px rgba(165, 30, 63, 0.2);
}

.value-icon i {
    color: var(--primary-color);
    font-size: 1.5rem;
}

.value-title {
    color: var(--text-dark);
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 1rem;
    text-align: center;
}

.value-description {
    color: var(--text-dark);
    font-size: 1rem;
    line-height: 1.6;
    flex-grow: 1;
    text-align: center;
}

/* Responsive Design - Hero Section */
@media (max-width: 768px) {
    .about-hero-section {
        background-attachment: scroll;
        min-height: 350px;
        padding: 80px 15px 60px 15px;
        margin-top: 70px;
    }
    
    .about-hero-section::before {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .about-hero-content {
        max-width: 100%;
    }
    .about-story-title {
        font-size: 1.5rem;
    }
    
    .about-story-text p {
        font-size: 0.9rem;
    }
    
    .values-title {
        font-size: 1.3rem;
    }
    
    .values-list li {
        font-size: 0.85rem;
    }
    
    .values-column {
        gap: 1rem;
    }
    
    .values-section-title {
        font-size: 1.6rem;
    }
    
    .value-card {
        padding: 1.5rem;
        text-align: center;
    }
    
    .value-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 1rem auto;
    }
    
    .value-icon i {
        font-size: 1.2rem;
    }
    
    .value-title {
        font-size: 1.1rem;
        text-align: center;
    }
    
    .value-description {
        font-size: 0.95rem;
        text-align: center;
    }
    
    .image-layout {
        flex-direction: column;
        gap: 1rem;
    }
    
    .left-column,
    .right-column {
        flex: none;
        width: 100%;
    }
    
    .main-image {
        width: 100%;
        max-width: 100%;
    }
    
    .values-title {
        font-size: 1.3rem;
    }
    
    .values-list li {
        font-size: 0.95rem;
    }
}

@media (max-width: 576px) {
    .about-hero-section {
        background-attachment: scroll;
        min-height: 300px;
        padding: 70px 10px 50px 10px;
        margin-top: 60px;
    }
    
    .about-hero-section::before {
        background: rgba(255, 255, 255, 0.92);
    }
    
    .container.py-5[style*="margin-top: 50px"] {
        margin-top: 30px !important;
        padding-top: 2rem !important;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .section-subtitle {
        font-size: 0.9rem;
    }
    
    .section-description {
        font-size: 0.95rem;
    }
    
    .about-story-title {
        font-size: 1.2rem;
    }
    
    .about-story-text p {
        font-size: 0.9rem;
        margin-bottom: 0.8rem;
    }
    
    .values-section-title {
        font-size: 1.4rem;
    }
    
    .value-card {
        padding: 1rem;
    }
    
    .value-icon {
        width: 45px;
        height: 45px;
    }
    
    .value-icon i {
        font-size: 1.1rem;
    }
    
    .value-title {
        font-size: 1rem;
    }
    
    .value-description {
        font-size: 0.85rem;
    }
    
    .image-layout {
        flex-direction: column;
        gap: 1rem;
    }
    
    .left-column,
    .right-column {
        flex: none;
        width: 100%;
    }
    
    .main-image {
        max-width: 100%;
        width: 100%;
    }
}
</style>

<?php include 'includes/footer.php'; ?>

