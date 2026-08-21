    </main>

    <!-- ==================== FOOTER SECTION ==================== -->
    <?php if ($active_theme === 'home3.php'): ?>
        <!-- Theme 3: Bengali Primary School Footer -->
        <footer style="background-color: #0f172a; color: #94a3b8; padding: 50px 5% 25px; font-size: 0.95rem; border-top: 5px solid #ea580c; margin-top: 40px;">
            <div style="max-width: 1200px; margin: 0 auto 35px; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px;">
                <!-- Col 1 -->
                <div>
                    <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 15px; border-left: 4px solid #ea580c; padding-left: 10px; font-weight: 700;">আমাদের বিদ্যালয়</h3>
                    <p style="font-size: 0.88rem; line-height: 1.6; color: #cbd5e1;"><?= htmlspecialchars($settings['footer_about_text'] ?? 'আমাদের বিদ্যালয় শিশুদের আধুনিক শিক্ষা ও নৈতিক মানদণ্ড বজায় রেখে আদর্শ মানুষ হিসেবে গড়ে তুলতে কাজ করে যাচ্ছে।') ?></p>
                    <div style="display: flex; gap: 12px; margin-top: 15px;">
                        <?php if(!empty($settings['social_facebook'])): ?>
                            <a href="<?= htmlspecialchars($settings['social_facebook']) ?>" target="_blank" style="color: #fff; font-size: 1.2rem;"><i class="fa-brands fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($settings['social_youtube'])): ?>
                            <a href="<?= htmlspecialchars($settings['social_youtube']) ?>" target="_blank" style="color: #fff; font-size: 1.2rem;"><i class="fa-brands fa-youtube"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Col 2 -->
                <div>
                    <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 15px; border-left: 4px solid #ea580c; padding-left: 10px; font-weight: 700;">গুরুত্বপূর্ণ লিংক</h3>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.88rem;">
                        <?php 
                        $quick_portals = json_decode($settings['footer_quick_portals'] ?? '[]', true);
                        if (is_array($quick_portals) && !empty($quick_portals)): 
                            foreach ($quick_portals as $portal):
                        ?>
                            <li style="margin-bottom: 8px;"><a href="<?= htmlspecialchars($portal['url'] ?? '#') ?>" style="color: #cbd5e1; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#cbd5e1'"><?= htmlspecialchars($portal['title']) ?></a></li>
                        <?php 
                            endforeach;
                        else: 
                        ?>
                            <li style="margin-bottom: 8px;"><a href="http://www.dpe.gov.bd/" target="_blank" style="color: #cbd5e1; text-decoration: none;">প্রাথমিক শিক্ষা অধিদপ্তর</a></li>
                            <li style="margin-bottom: 8px;"><a href="https://mopme.gov.bd/" target="_blank" style="color: #cbd5e1; text-decoration: none;">প্রাথমিক ও গণশিক্ষা মন্ত্রণালয়</a></li>
                            <li style="margin-bottom: 8px;"><a href="http://www.nctb.gov.bd/" target="_blank" style="color: #cbd5e1; text-decoration: none;">এনসিটিবি (NCTB) পাঠ্যবই</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 15px; border-left: 4px solid #ea580c; padding-left: 10px; font-weight: 700;">যোগাযোগের ঠিকানা</h3>
                    <p style="font-size: 0.88rem; margin-bottom: 8px; color: #cbd5e1;"><i class="fa-solid fa-location-dot" style="color: #ea580c; margin-right: 8px;"></i> <?= htmlspecialchars($settings['contact_address'] ?? 'বাংলাদেশ।') ?></p>
                    <p style="font-size: 0.88rem; margin-bottom: 8px; color: #cbd5e1;"><i class="fa-solid fa-phone" style="color: #ea580c; margin-right: 8px;"></i> <?= htmlspecialchars($settings['contact_phone'] ?? '+৮৮০১৭১১-XXXXXX') ?></p>
                    <p style="font-size: 0.88rem; color: #cbd5e1;"><i class="fa-solid fa-envelope" style="color: #ea580c; margin-right: 8px;"></i> <?= htmlspecialchars($settings['contact_email'] ?? 'mail@school.edu.bd') ?></p>
                </div>

                <!-- Col 4 -->
                <div>
                    <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 15px; border-left: 4px solid #ea580c; padding-left: 10px; font-weight: 700;">আমাদের অবস্থান</h3>
                    <div style="background-color: #1e293b; border-radius: 8px; height: 120px; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: #94a3b8; text-align: center; padding: 10px;">
                        <span><?= htmlspecialchars($settings['school_name'] ?? 'প্রাথমিক বিদ্যালয়') ?></span>
                    </div>
                </div>
            </div>

            <div style="max-width: 1200px; margin: 0 auto; padding-top: 20px; border-top: 1px solid #1e293b; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 15px; font-size: 0.82rem; color: #64748b;">
                <div>
                    <p><?= htmlspecialchars($settings['footer_copyright_text'] ?? ('&copy; ' . date('Y') . ' সর্বস্বত্ব সংরক্ষিত।')) ?></p>
                    <p style="margin-top: 4px;"><?= htmlspecialchars($settings['footer_designed_text'] ?? 'গণপ্রজাতন্ত্রী বাংলাদেশ সরকার কর্তৃক অনুমোদিত।') ?></p>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-end;">
                    <span class="inline-flex items-center gap-2" style="display:inline-flex; align-items:center; gap:10px;">
                        <span style="color: #e2e8f0; font-size: 13.5px; font-weight: 600; letter-spacing: 0.2px;">ডেভেলপমেন্ট By :</span>
                        <a href="https://netfie.com" target="_blank" title="Netfie.com" class="inline-flex items-center hover:opacity-90 hover:scale-105 transition-all duration-200" style="display:inline-flex; align-items:center; transition: transform 0.2s ease;">
                            <img src="https://netfie.com/wp-content/uploads/2025/03/Netfie__1_-removebg-preview-450x174.png.webp" alt="Netfie" class="h-8 md:h-9 w-auto inline-block align-middle drop-shadow-md brightness-110" style="height: 38px; width: auto; vertical-align: middle; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.45));">
                        </a>
                    </span>
                </div>
            </div>
        </footer>

    <?php elseif ($active_theme === 'home2.php'): ?>
        <!-- Theme 2: Prestigious College / University Footer -->
        <footer style="background-color: #0b2240; color: #e2e8f0; padding: 50px 40px 25px; margin-top: 50px; border-top: 4px solid #c5a059;">
            <div style="max-width: 1400px; margin: 0 auto 35px; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 30px;">
                <!-- Col 1 -->
                <div>
                    <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 15px; border-bottom: 2px solid #c5a059; padding-bottom: 8px; font-weight: 700;">About <?= htmlspecialchars($settings['school_name'] ?? 'University') ?></h3>
                    <p style="font-size: 0.85rem; line-height: 1.6; color: #cbd5e1;"><?= htmlspecialchars($settings['footer_about_text'] ?? 'Authorized by the Government and recognized by the UGC, emphasizing research, innovation, and ethical development.') ?></p>
                    <div style="display: flex; gap: 12px; margin-top: 15px;">
                        <?php if(!empty($settings['social_facebook'])): ?>
                            <a href="<?= htmlspecialchars($settings['social_facebook']) ?>" target="_blank" style="color: #c5a059; font-size: 1.1rem;"><i class="fa-brands fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($settings['social_twitter'])): ?>
                            <a href="<?= htmlspecialchars($settings['social_twitter']) ?>" target="_blank" style="color: #c5a059; font-size: 1.1rem;"><i class="fa-brands fa-x-twitter"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Col 2 -->
                <div>
                    <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 15px; border-bottom: 2px solid #c5a059; padding-bottom: 8px; font-weight: 700;">Academic Units</h3>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem;">
                        <?php 
                        $academic_units = json_decode($settings['footer_academic_units'] ?? '[]', true);
                        if (is_array($academic_units) && !empty($academic_units)): 
                            foreach ($academic_units as $unit):
                        ?>
                            <li style="margin-bottom: 6px;"><a href="<?= htmlspecialchars($unit['url'] ?? '#') ?>" style="color: #cbd5e1; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#c5a059'" onmouseout="this.style.color='#cbd5e1'"><?= htmlspecialchars($unit['title']) ?></a></li>
                        <?php 
                            endforeach;
                        else: 
                        ?>
                            <li style="margin-bottom: 6px;"><a href="#" style="color: #cbd5e1; text-decoration: none;">Computer Science & Engineering</a></li>
                            <li style="margin-bottom: 6px;"><a href="#" style="color: #cbd5e1; text-decoration: none;">Electrical & Electronic Engineering</a></li>
                            <li style="margin-bottom: 6px;"><a href="#" style="color: #cbd5e1; text-decoration: none;">School of Business Administration</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 15px; border-bottom: 2px solid #c5a059; padding-bottom: 8px; font-weight: 700;">Quick Portals</h3>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.85rem;">
                        <?php 
                        $quick_portals = json_decode($settings['footer_quick_portals'] ?? '[]', true);
                        if (is_array($quick_portals) && !empty($quick_portals)): 
                            foreach ($quick_portals as $portal):
                        ?>
                            <li style="margin-bottom: 6px;"><a href="<?= htmlspecialchars($portal['url'] ?? '#') ?>" style="color: #cbd5e1; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#c5a059'" onmouseout="this.style.color='#cbd5e1'"><?= htmlspecialchars($portal['title']) ?></a></li>
                        <?php 
                            endforeach;
                        else: 
                        ?>
                            <li style="margin-bottom: 6px;"><a href="#" style="color: #cbd5e1; text-decoration: none;">Student Online Registration</a></li>
                            <li style="margin-bottom: 6px;"><a href="#" style="color: #cbd5e1; text-decoration: none;">Faculty Directory</a></li>
                            <li style="margin-bottom: 6px;"><a href="#" style="color: #cbd5e1; text-decoration: none;">Library Catalogs</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Col 4 -->
                <div>
                    <h3 style="color: #fff; font-size: 1.1rem; margin-bottom: 15px; border-bottom: 2px solid #c5a059; padding-bottom: 8px; font-weight: 700;">Contact Address</h3>
                    <p style="font-size: 0.85rem; margin-bottom: 6px; color: #cbd5e1;"><i class="fa-solid fa-location-dot" style="color: #c5a059; margin-right: 8px;"></i> <?= htmlspecialchars($settings['contact_address'] ?? 'Campus Address') ?></p>
                    <p style="font-size: 0.85rem; margin-bottom: 6px; color: #cbd5e1;"><i class="fa-solid fa-phone" style="color: #c5a059; margin-right: 8px;"></i> <?= htmlspecialchars($settings['contact_phone'] ?? '') ?></p>
                    <p style="font-size: 0.85rem; color: #cbd5e1;"><i class="fa-solid fa-envelope" style="color: #c5a059; margin-right: 8px;"></i> <?= htmlspecialchars($settings['contact_email'] ?? '') ?></p>
                </div>
            </div>

            <div style="max-width: 1400px; margin: 0 auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 15px; font-size: 0.82rem; color: #94a3b8;">
                <div>
                    <p><?= htmlspecialchars($settings['footer_copyright_text'] ?? ('© ' . date('Y') . ' All Rights Reserved.')) ?></p>
                    <p style="margin-top: 4px;"><?= htmlspecialchars($settings['footer_designed_text'] ?? '') ?></p>
                </div>
                <div style="display: flex; align-items: center; justify-content: flex-end;">
                    <span class="inline-flex items-center gap-2" style="display:inline-flex; align-items:center; gap:10px;">
                        <span style="color: #e2e8f0; font-size: 13.5px; font-weight: 600; letter-spacing: 0.2px;">ডেভেলপমেন্ট By :</span>
                        <a href="https://netfie.com" target="_blank" title="Netfie.com" class="inline-flex items-center hover:opacity-90 hover:scale-105 transition-all duration-200" style="display:inline-flex; align-items:center; transition: transform 0.2s ease;">
                            <img src="https://netfie.com/wp-content/uploads/2025/03/Netfie__1_-removebg-preview-450x174.png.webp" alt="Netfie" class="h-8 md:h-9 w-auto inline-block align-middle drop-shadow-md brightness-110" style="height: 38px; width: auto; vertical-align: middle; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.45));">
                        </a>
                    </span>
                </div>
            </div>
        </footer>

    <?php else: ?>
        <!-- Theme 1: Standard Modern School Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-10">
            <div class="container mx-auto px-4">
                <div class="flex justify-center space-x-4 mb-4">
                    <?php if (!empty($settings['social_facebook'])): ?><a href="<?= htmlspecialchars($settings['social_facebook']) ?>" target="_blank" class="hover:text-blue-400"><i class="ri-facebook-box-fill text-2xl"></i></a><?php endif; ?>
                    <?php if (!empty($settings['social_twitter'])): ?><a href="<?= htmlspecialchars($settings['social_twitter']) ?>" target="_blank" class="hover:text-blue-400"><i class="ri-twitter-x-fill text-2xl"></i></a><?php endif; ?>
                    <?php if (!empty($settings['social_instagram'])): ?><a href="<?= htmlspecialchars($settings['social_instagram']) ?>" target="_blank" class="hover:text-pink-400"><i class="ri-instagram-fill text-2xl"></i></a><?php endif; ?>
                    <?php if (!empty($settings['social_youtube'])): ?><a href="<?= htmlspecialchars($settings['social_youtube']) ?>" target="_blank" class="hover:text-red-500"><i class="ri-youtube-fill text-2xl"></i></a><?php endif; ?>
                </div>
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-700">
                    <div class="text-center md:text-left">
                        <p><?= str_replace('© 2025', '© ' . date('Y'), htmlspecialchars($settings['footer_copyright_text'] ?? '© ' . date('Y') . ' School. All rights reserved.')) ?></p>
                        <p class="text-sm text-gray-400 mt-1"><?= htmlspecialchars($settings['contact_address'] ?? '') ?></p>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: flex-end;">
                        <span class="inline-flex items-center gap-2" style="display:inline-flex; align-items:center; gap:10px;">
                            <span style="color: #e2e8f0; font-size: 13.5px; font-weight: 600; letter-spacing: 0.2px;">ডেভেলপমেন্ট By :</span>
                            <a href="https://netfie.com" target="_blank" title="Netfie.com" class="inline-flex items-center hover:opacity-90 hover:scale-105 transition-all duration-200" style="display:inline-flex; align-items:center; transition: transform 0.2s ease;">
                                <img src="https://netfie.com/wp-content/uploads/2025/03/Netfie__1_-removebg-preview-450x174.png.webp" alt="Netfie" class="h-8 md:h-9 w-auto inline-block align-middle drop-shadow-md brightness-110" style="height: 38px; width: auto; vertical-align: middle; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.45));">
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <!-- AOS Animation Library JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof AOS !== "undefined") {
                AOS.init({
                    duration: 750,
                    easing: 'ease-out-cubic',
                    once: true,
                    offset: 40
                });
            }
        });
    </script>
</body>
</html>