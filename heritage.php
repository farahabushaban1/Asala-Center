<?php
// Include required files
require_once 'config/config.php';
require_once 'config/database.php';

// Heritage page content
$currentLang = getCurrentLanguage();
$lang = loadLanguage($currentLang);

// Include header
include 'includes/header.php';
?>

<!-- Heritage Section 1: Introduction -->
<section class="heritage-intro py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="section-title">
                    <?= $currentLang === 'ar' ? 'تراثنا الفلسطيني' : 'Our Palestinian Heritage' ?>
                </h1>
                <div class="title-underline"></div>
                <p class="section-subtitle">
                    <?= $currentLang === 'ar' 
                        ? 'التطريز الفلسطيني ليس مجرد زخرفة، بل هو لغة تحكي قصص الأجداد وتراث الوطن العريق' 
                        : 'Palestinian embroidery is not just an ornament, but a language that tells stories of ancestors and the ancient heritage of the homeland' ?>
                </p>
            </div>
        </div>
    </div>
</section>



<!-- Heritage Section 2: History with Image -->
<section class="heritage-history-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="heritage-image">
                    <img src="assets/images/Heritage.jpeg" alt="<?= $currentLang === 'ar' ? 'تطريز فلسطيني تقليدي' : 'Traditional Palestinian Embroidery' ?>" class="img-fluid heritage-main-image">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="heritage-content">
                        <h2 class="heritage-title">
                        <?= $currentLang === 'ar' ? 'تاريخ التطريز الفلسطيني' : 'History of Palestinian Embroidery' ?>
                        </h2>
                        <p class="heritage-text">
                            <?= $currentLang === 'ar' 
                            ? 'يعود التطريز الفلسطيني إلى آلاف السنين، حيث كان النساء الفلسطينيات يطرزن أثوابهن بنقوش تحكي قصص القرى والمدن والتقاليد العريقة' 
                            : 'Palestinian embroidery dates back thousands of years, where Palestinian women embroidered their dresses with motifs that tell stories of villages, cities, and ancient traditions' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Heritage Section 3: Symbols and Meanings -->
<section class="heritage-symbols-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="heritage-content">
                    <h2 class="heritage-title">
                        <?= $currentLang === 'ar' ? 'رموز ومعاني النقوش' : 'Symbols and Meanings of Motifs' ?>
                    </h2>
                    <p class="heritage-text">
                        <?= $currentLang === 'ar' 
                            ? 'كل نقشة في التطريز الفلسطيني لها معنى عميق - من أشجار السرو التي ترمز للحياة الأبدية، إلى الورود التي تعبر عن الجمال والنعومة' 
                            : 'Every motif in Palestinian embroidery has a deep meaning - from cypress trees that symbolize eternal life, to roses that express beauty and softness' ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="heritage-image">
                    <img src="assets/images/Heritage1.jpeg" alt="<?= $currentLang === 'ar' ? 'رموز التطريز الفلسطيني' : 'Palestinian Embroidery Symbols' ?>" class="img-fluid heritage-main-image">
                </div>
            </div>
        </div>
    </div>
</section>


<style>
/* Heritage Section Styles */
.heritage-intro {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    padding-top: 120px !important; /* Increased space for navbar */
    padding-bottom: 60px !important;
    margin-top: 0 !important;
    position: relative !important;
    z-index: 1 !important;
}

.heritage-history-section {
    background: white;
    padding: 80px 0;
}

.heritage-symbols-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 80px 0;
}

.section-title {
    color: #A51E3F !important;
    font-size: 3.5rem !important;
    font-weight: 800 !important;
    margin-bottom: 1.5rem !important;
    text-shadow: 0 2px 4px rgba(165, 30, 63, 0.1) !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    display: block !important;
    width: 100% !important;
    text-align: center !important;
    position: relative !important;
    z-index: 10 !important;
    margin-top: 0 !important;
    padding-top: 0 !important;
    line-height: 1.2 !important;
}

.title-underline {
    width: 120px !important;
    height: 4px !important;
    background: linear-gradient(90deg, #A51E3F 0%, #8a1a35 100%) !important;
    margin: 0 auto 2rem !important;
    border-radius: 2px !important;
    box-shadow: 0 2px 8px rgba(165, 30, 63, 0.3) !important;
    display: block !important;
}

.section-subtitle {
    font-size: 1.3rem !important;
    color: #555 !important;
    max-width: 700px !important;
    margin: 0 auto !important;
    line-height: 1.8 !important;
    font-weight: 400 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    display: block !important;
    margin-top: 1rem !important;
}

.heritage-content {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    border: 1px solid rgba(165, 30, 63, 0.1);
}

.heritage-title {
    color: #A51E3F;
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
}

.heritage-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: #A51E3F;
    border-radius: 2px;
}

.heritage-text {
    font-size: 1.15rem;
    line-height: 1.9;
    color: #444;
    margin-bottom: 2rem;
    text-align: justify;
}

.heritage-image {
    text-align: center;
    margin-bottom: 2rem;
}

.heritage-main-image {
    max-width: 100%;
    height: auto;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.heritage-main-image:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 40px rgba(165, 30, 63, 0.2);
}

/* RTL Support for Heritage Sections */
[dir="rtl"] .heritage-content {
    text-align: right;
}

[dir="rtl"] .heritage-title::after {
    left: auto;
    right: 0;
}



/* Responsive Design */
@media (max-width: 768px) {
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
    }
    
    .heritage-content {
        padding: 2rem;
    }
    
    .heritage-image {
        margin-bottom: 2rem;
    }
    
    .heritage-intro {
        padding-top: 100px;
        padding-bottom: 40px;
    }
}

@media (max-width: 576px) {
    .section-title {
        font-size: 2rem;
        font-weight: 700;
    }
    
    .heritage-title {
        font-size: 1.6rem;
    }
    
    .heritage-content {
        padding: 1.5rem;
    }
    
    .heritage-text {
        font-size: 1rem;
    }
    
    .heritage-intro {
        padding-top: 80px;
        padding-bottom: 30px;
    }
}

/* RTL Support */
[dir="rtl"] .heritage-title::after {
    left: auto;
    right: 0;
}

[dir="rtl"] .section-title {
    text-transform: none;
    letter-spacing: normal;
}

[dir="rtl"] .section-subtitle {
    text-transform: none;
    letter-spacing: normal;
}
</style>

<?php
// Include footer
include 'includes/footer.php';
?>
