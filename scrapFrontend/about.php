<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    /* ABOUT SECTION */
    .about-section {
      background-color: var(--card-bg);
      color: var(--text-color);
      border-radius: 15px;
      padding: 25px 30px;
      margin: 40px auto;
      max-width: 900px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      line-height: 1.7;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .about-section h2 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 15px;
      color: var(--text-color);
      text-align: center;
    }

    .about-section p {
      font-size: 1rem;
      margin-bottom: 10px;
      text-align: justify;
    }

    .about-section .highlight {
      color: #2563eb;
      font-weight: 600;
    }

    body.dark-mode .about-section {
      background-color: #161b22;
      color: #e6e6e6;
      box-shadow: 0 2px 10px rgba(255, 255, 255, 0.05);
    }

    body.dark-mode .about-section .highlight {
      color: #58a6ff;
    }
  </style>
</head>

<body>
  <section class="about-section">
    <h2>Tentang Aplikasi</h2>
    <p>
      I created this website <strong>to help myself </strong>find a lecturer's class schedule or to find out if a class might be in use.
    </p>
    <p>
      I don't mean to break any rules about data <strong> scraping</strong>, but I think technology is there <strong>to make things easier</strong>.
    </p>
  </section>
</body>

</html>