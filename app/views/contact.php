<?php viewName('header', ['title' => 'Contact']); ?>
<head>
<style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
            color: #EFE9D5;
        }
        .contact-header {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/mywebsite/public/assets/cafe-bg.jpg');
            background-size: cover;
            background-position: center;
            padding: 80px 0;
            text-align: center;
        }
        .contact-header h1 {
            font-size: 2.5rem; /* Moderate size for "Contact Us" */
            font-weight: bold;
            color: black;
        }
        .contact-header p {
            font-size: 1.2rem;
            color: #EFE9D5;
        }
        .contact-section {
            padding: 50px 0;
        }
        .contact-info {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
        }
        .contact-info h3 {
            color: #27445D;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .contact-info p {
            font-size: 1rem;
            color: #333;
            margin: 8px 0;
        }
        .contact-info a {
            color: #27445D;
            text-decoration: none;
        }
        .contact-info a:hover {
            text-decoration: underline;
        }
        .contact-form {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .contact-form h3 {
            color: #27445D;
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .form-control {
            border-color: #27445D;
            color: #333;
        }
        .form-control:focus {
            border-color: #1d3346;
            box-shadow: 0 0 5px rgba(39, 68, 93, 0.5);
            color: #333;
        }
        .form-control::placeholder {
            color: #6c757d;
        }
        .btn-primary {
            background-color: #27445D;
            border-color: #27445D;
            color: #EFE9D5;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background-color: #1d3346;
            border-color: #1d3346;
        }
        @media (max-width: 768px) {
            .contact-header h1 {
                font-size: 2rem;
            }
            .contact-header p {
                font-size: 1rem;
            }
            .contact-section {
                padding: 30px 0;
            }
            .contact-info, .contact-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <section class="contact-section h-100">
        <div class="container">
            <div class="row">
                <!-- Contact Information -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="contact-info">
                        <h3>Get in Touch</h3>
                        <p><strong>Email:</strong> <a href="mailto:huynhkhoa03012004@gmail.com">huynhkhoa03012004@gmail.com</a></p>
                        <p><strong>Phone:</strong> <a href="tel:+84932523714">0932523714</a></p>
                        <p><strong>Address:</strong> Ho Chi Minh City, Vietnam</p>
                        <p>Reach out to us for support or to share your café recommendations!</p>
                    </div>
                </div>
                <!-- Contact Form -->
                <div class="col-lg-6 col-md-12 ">
                    <div class="contact-form">
                        <h3>Send a Message</h3>
                        <form id="contactForm" action="/mywebsite/public/api/submit_contact.php" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label text-black">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-black">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label text-black">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Your message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
