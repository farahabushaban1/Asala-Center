<?php
// Get current language if not already set
if (!isset($currentLang)) {
    $currentLang = getCurrentLanguage();
    $lang = loadLanguage($currentLang);
}
?>
                    <!-- Footer -->
                <footer class="footer" id="footer">
        <div class="container">
            <div class="row">
                <!-- Column 1: Logo and Description -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <img src="assets/images/IMG_0579 2.png" alt="Asala Center" class="footer-logo-img">
                        </div>
                        <div class="footer-description">
                            <p><?= $lang['footer_description'] ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Column 2: Quick Links -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section quick-links-section">
                        <h5 class="footer-title"><?= $lang['quick_links'] ?></h5>
                        <ul class="footer-links">
                            <li><a href="./index.php?lang=<?= $currentLang ?>"><?= $lang['home'] ?></a></li>
                            <li><a href="./index.php?page=about&lang=<?= $currentLang ?>"><?= $lang['about'] ?></a></li>
                            <li><a href="./index.php?page=heritage&lang=<?= $currentLang ?>"><?= $lang['heritage'] ?></a></li>
                            <li><a href="./index.php?page=product&lang=<?= $currentLang ?>"><?= $lang['products'] ?></a></li>
                            <li><a href="#footer"><?= $lang['contact'] ?></a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Column 3: Contact -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5 class="footer-title"><?= $lang['contact_us'] ?></h5>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= getSetting('contact_address', 'Palestine - Gaza') ?></span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:<?= getSetting('contact_email', 'AsalaCenter@gmail.com') ?>" class="contact-link"><?= getSetting('contact_email', 'AsalaCenter@gmail.com') ?></a>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:<?= getSetting('contact_phone', '0592310435') ?>" class="contact-link"><?= getSetting('contact_phone', '0592310435') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Column 4: Social Media -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5 class="footer-title"><?= $lang['follow_us'] ?></h5>
                        <div class="social-links">
                            <a href="<?= getSetting('facebook_url', '#') ?>" class="social-link" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="<?= getSetting('instagram_url', '#') ?>" class="social-link" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://wa.me/<?= getSetting('whatsapp_number', '0592310435') ?>" class="social-link" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <p class="copyright text-center">
                            &copy; <?= date('Y') ?> <?= $lang['asala_center_eastern_embroidery'] ?>. <?= $lang['all_rights_reserved'] ?>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>


