<?php
// Check GD extension status
echo "<h2>🔍 فحص مكتبة GD للصور</h2>";

if (extension_loaded('gd')) {
    echo "<p style='color: green; font-weight: bold;'>✅ مكتبة GD مفعلة!</p>";
    
    // Check specific functions
    $functions = [
        'imagecreatefromjpeg' => 'JPEG Support',
        'imagecreatefrompng' => 'PNG Support', 
        'imagecreatefromgif' => 'GIF Support',
        'imagecreatefromwebp' => 'WebP Support'
    ];
    
    echo "<h3>الميزات المدعومة:</h3>";
    echo "<ul>";
    foreach ($functions as $function => $name) {
        if (function_exists($function)) {
            echo "<li style='color: green;'>✅ $name</li>";
        } else {
            echo "<li style='color: red;'>❌ $name</li>";
        }
    }
    echo "</ul>";
    
    // Get GD info
    $gd_info = gd_info();
    echo "<h3>معلومات GD:</h3>";
    echo "<ul>";
    foreach ($gd_info as $key => $value) {
        if (is_bool($value)) {
            $value = $value ? 'نعم' : 'لا';
        }
        echo "<li><strong>$key:</strong> $value</li>";
    }
    echo "</ul>";
    
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ مكتبة GD غير مفعلة</p>";
    echo "<p>لكن لا تقلق! الكود يدعم رفع الصور بدون تحجيم.</p>";
}

echo "<hr>";
echo "<h3>🎯 الخطوات التالية:</h3>";
echo "<ol>";
echo "<li>إذا كانت GD غير مفعلة، اتبع التعليمات في ملف ENABLE_GD_EXTENSION.md</li>";
echo "<li>أعد تشغيل Apache بعد التعديل</li>";
echo "<li>جرب رفع صورة في admin/products.php</li>";
echo "</ol>";

echo "<p><a href='admin/products.php' style='background: #A51E3F; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>اذهب إلى صفحة المنتجات</a></p>";
?>






