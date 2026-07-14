</main>
<footer class="bg-gray-800 text-white py-8 mt-10">
    <div class="container mx-auto px-4 text-center">
        <div class="flex justify-center space-x-4 mb-4">
            <?php if (!empty($settings['social_facebook'])): ?><a href="<?= htmlspecialchars($settings['social_facebook']) ?>" target="_blank"><i class="ri-facebook-box-fill text-2xl"></i></a><?php endif; ?>
            <?php if (!empty($settings['social_twitter'])): ?><a href="<?= htmlspecialchars($settings['social_twitter']) ?>" target="_blank"><i class="ri-twitter-x-fill text-2xl"></i></a><?php endif; ?>
            <?php if (!empty($settings['social_instagram'])): ?><a href="<?= htmlspecialchars($settings['social_instagram']) ?>" target="_blank"><i class="ri-instagram-fill text-2xl"></i></a><?php endif; ?>
            <?php if (!empty($settings['social_youtube'])): ?><a href="<?= htmlspecialchars($settings['social_youtube']) ?>" target="_blank"><i class="ri-youtube-fill text-2xl"></i></a><?php endif; ?>
        </div>
        <p><?= str_replace('© 2025', '© ' . date('Y'), htmlspecialchars($settings['footer_copyright_text'])) ?></p>
        <p class="text-sm text-gray-400 mt-1"><?= htmlspecialchars($settings['contact_address']) ?></p>
    </div>
</footer>