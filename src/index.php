<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Eventify | Gestió d’entrades</title>

    <!-- Estilos globales -->
    <link rel="stylesheet" href="../css/base.css">

    <!-- Estilos por componente -->
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">

    <!-- Estilos específicos de esta vista -->
    <link rel="stylesheet" href="../css/index.css">
</head>

<body>

    <?php include "header.php"; ?>

    <main>

        <!-- HERO -->
        <section class="hero">
            <div class="container hero-content">

                <div class="hero-text">
                    <h1>La plataforma per gestionar i vendre entrades</h1>
                    <p>
                        Eventify et permet crear esdeveniments, gestionar aforaments i vendre entrades
                        de manera ràpida i segura.
                    </p>

                    <div class="hero-actions">
                        <a href="#" class="btn btn-primary">Crea un esdeveniment</a>
                        <a href="#esdeveniments" class="btn btn-outline">Veure esdeveniments</a>
                    </div>
                </div>

                <div class="hero-card">
                    <h2>Esdeveniments destacats</h2>
                    <ul class="event-list">
                        <li class="event-item">
                            <span class="event-name">Nit Indie</span>
                            <span class="event-meta">Barcelona · 12/12/2025</span>
                        </li>
                        <li class="event-item">
                            <span class="event-name">Tech4All</span>
                            <span class="event-meta">Online · 20/01/2026</span>
                        </li>
                        <li class="event-item">
                            <span class="event-name">Festival Gastronòmic</span>
                            <span class="event-meta">Girona · 05/03/2026</span>
                        </li>
                    </ul>

                    <a href="#esdeveniments" class="link">Veure tots →</a>
                </div>

            </div>
        </section>

        <!-- SECCIONS EXTRA (las puedes rellenar luego) -->
        <section id="com-funciona" class="section">
            <div class="container">
                <h2>Com funciona Eventify?</h2>
                <p class="section-subtitle">En tres passos ja tens el teu esdeveniment en marxa.</p>

                <div class="grid-3">
                    <div class="card step-card">
                        <span class="step-number">1</span>
                        <h3>Crea el teu esdeveniment</h3>
                        <p>Introdueix nom, data, aforament i tipus d’entrades.</p>
                    </div>

                    <div class="card step-card">
                        <span class="step-number">2</span>
                        <h3>Comparteix l’enllaç</h3>
                        <p>Publica les teves entrades en xarxes, web o email.</p>
                    </div>

                    <div class="card step-card">
                        <span class="step-number">3</span>
                        <h3>Controla accessos</h3>
                        <p>Valida els QR el dia de l’esdeveniment.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="esdeveniments" class="section section-alt">
            <div class="container">
                <h2>Esdeveniments en curs</h2>
                <p class="section-subtitle">Exemple d'esdeveniments generats des de base de dades.</p>

                <div class="grid-3">
                    <article class="card event-card">
                        <h3>Concert Rock Nights</h3>
                        <p class="event-location">Sala Razzmatazz · Barcelona</p>
                        <p class="event-date">22/02/2026 · 21:00h</p>
                        <p class="event-desc">Concert amb bandes emergents.</p>
                        <a href="#" class="btn btn-small btn-primary">Comprar entrades</a>
                    </article>

                    <article class="card event-card">
                        <h3>Jornades Web Dev</h3>
                        <p class="event-location">Online</p>
                        <p class="event-date">10/03/2026 · 18:00h</p>
                        <p class="event-desc">Tallers sobre tecnologies web modernes.</p>
                        <a href="#" class="btn btn-small btn-primary">Comprar entrades</a>
                    </article>

                    <article class="card event-card">
                        <h3>Mercat d'Art i Disseny</h3>
                        <p class="event-location">Palma</p>
                        <p class="event-date">30/04/2026 · 10:00h</p>
                        <p class="event-desc">Artistes locals presenten creacions.</p>
                        <a href="#" class="btn btn-small btn-primary">Comprar entrades</a>
                    </article>
                </div>
            </div>
        </section>

    </main>

    <?php include "footer.php"; ?>

</body>
</html>
