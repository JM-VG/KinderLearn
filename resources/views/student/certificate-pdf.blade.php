<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: Georgia, 'Times New Roman', serif;
    background: #f5f0e8;
    width: 297mm;
    height: 210mm;
    overflow: hidden;
}

.page {
    width: 297mm;
    height: 210mm;
    background: #f5f0e8;
    position: relative;
    padding: 12mm 16mm 10mm;
}

/* ── Gold border frame ── */
.border-outer {
    position: absolute;
    inset: 5mm;
    border: 3px solid #b8962e;
}
.border-inner {
    position: absolute;
    inset: 7mm;
    border: 1px solid #b8962e;
}

/* ── Corner medals ── */
.medal {
    position: absolute;
    font-size: 52px;
    line-height: 1;
}
.medal-tl { top: 8mm; left: 10mm; }
.medal-tr { top: 8mm; right: 10mm; }

/* ── Logo ── */
.logo {
    position: absolute;
    top: 10mm;
    left: 50%;
    transform: translateX(-50%);
    font-family: Arial Black, Arial, sans-serif;
    font-size: 18px;
    font-weight: 900;
    letter-spacing: -0.5px;
}
.logo-kinder { color: #1a1a1a; }
.logo-learn  { color: #e8392a; }

/* ── Main layout ── */
.content {
    position: absolute;
    inset: 14mm 18mm 10mm;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

/* ── CERTIFICATE heading ── */
.heading-certificate {
    font-family: Arial Black, Arial, sans-serif;
    font-size: 52px;
    font-weight: 900;
    color: #1a1a1a;
    letter-spacing: 4px;
    margin-top: 14mm;
    line-height: 1;
}

.heading-of-completion {
    font-family: Georgia, serif;
    font-size: 22px;
    color: #2a2a2a;
    letter-spacing: 8px;
    margin-top: 3px;
    margin-bottom: 6mm;
}

/* ── Presented To ── */
.presented-to {
    font-size: 13px;
    color: #444;
    letter-spacing: 1px;
    margin-bottom: 2mm;
}

/* ── Student name ── */
.student-name {
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 32px;
    font-weight: bold;
    color: #1a1a1a;
    border-bottom: 2px solid #1a1a1a;
    padding-bottom: 2px;
    min-width: 200mm;
    text-align: center;
    line-height: 1.3;
}

/* ── Body text ── */
.body-text {
    font-size: 12px;
    color: #333;
    margin-top: 4mm;
    line-height: 1.5;
    max-width: 200mm;
}

/* ── Signature row ── */
.sig-row {
    width: 100%;
    display: table;
    margin-top: 7mm;
    table-layout: fixed;
}
.sig-col {
    display: table-cell;
    width: 33.33%;
    text-align: center;
    vertical-align: bottom;
    padding: 0 6mm;
}
.sig-line {
    border-top: 1.5px solid #1a1a1a;
    margin-bottom: 3px;
}
.sig-name {
    font-size: 12px;
    font-weight: bold;
    color: #1a1a1a;
    line-height: 1.4;
}
.sig-role {
    font-size: 10px;
    color: #555;
    letter-spacing: 0.5px;
}

/* ── Star decorations ── */
.stars-left {
    position: absolute;
    left: 10mm;
    top: 50%;
    transform: translateY(-50%);
    font-size: 28px;
    line-height: 1.6;
    color: #f5c518;
}
.stars-right {
    position: absolute;
    right: 10mm;
    bottom: 18mm;
    font-size: 28px;
    line-height: 1.6;
    color: #f5c518;
}

/* ── KL badge ── */
.kl-badge {
    position: absolute;
    bottom: 10mm;
    left: 50%;
    transform: translateX(-50%);
    font-size: 8px;
    color: #888;
    letter-spacing: 1px;
}
</style>
</head>
<body>
<div class="page">

    <!-- Border frames -->
    <div class="border-outer"></div>
    <div class="border-inner"></div>

    <!-- Corner medals -->
    <div class="medal medal-tl">🏅</div>
    <div class="medal medal-tr">🏅</div>

    <!-- Logo top-center -->
    <div class="logo">
        <span class="logo-kinder">KINDER</span><span class="logo-learn">LEARN</span>
    </div>

    <!-- Star decorations left -->
    <div class="stars-left">⭐<br>☆<br>⭐</div>

    <!-- Star decorations right -->
    <div class="stars-right">⭐<br>☆</div>

    <!-- Main content -->
    <div class="content">

        <div class="heading-certificate">CERTIFICATE</div>
        <div class="heading-of-completion">OF COMPLETION</div>

        <div class="presented-to">Proudly Presented To:</div>

        <div class="student-name">{{ $student->name }}</div>

        <div class="body-text">
            Has successfully completed the activities for the KinderLearn program and<br>
            is ready to move to the next level
        </div>

        <!-- Signature row -->
        <div class="sig-row">
            <div class="sig-col">
                <div class="sig-line"></div>
                <div class="sig-name">Shiela Gipan</div>
                <div class="sig-role">Co-Founder</div>
            </div>
            <div class="sig-col">
                <div class="sig-line"></div>
                <div class="sig-name">Jhon Michael V. Gillado</div>
                <div class="sig-role">Founder</div>
            </div>
            <div class="sig-col">
                <div class="sig-line"></div>
                <div class="sig-name">{{ $adviser ? $adviser->name : '' }}</div>
                <div class="sig-role">Adviser</div>
            </div>
        </div>

    </div>

    <div class="kl-badge">KINDERLEARN · {{ now()->format('Y') }}</div>

</div>
</body>
</html>
