<?php
    include 'headerA.php';?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .contact-banner {
            position: relative;
            width: 100%;
            height: 350px;
            background: url('image/contact.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            text-align: left;
            color: white;
            padding-left: 50px;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(141, 198, 65, 0.5);
        }
        .content {
            position: relative;
            z-index: 2;
        }
        .contact-container {
            display: flex;
            justify-content: space-between;
            padding: 40px;
            flex-wrap: wrap;
        }
        .contact-details, .contact-form {
            flex: 1;
            padding: 20px;
            min-width: 300px;
        }
        .contact-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }
        .details-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .detail {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .detail i {
            font-size: 24px;
            color: teal;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e6f2f2;
            border-radius: 50%;
        }
        .map {
            margin-top: 20px;
            width: 100%;
            height: 300px;
        }
        .contact-form form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .contact-form textarea {
            height: 120px;
            resize: none;
        }
        .contact-form button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: teal;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .contact-form button:hover {
            background-color: darkcyan;
        }
    </style>
</head>
<body>
    <section class="contact-banner">
        <div class="overlay"></div>
        <div class="content">
            <h1 class="animated-text">Contact Us</h1>
            <p class="animated-text"><a href="home.php">Home</a> &gt; Contact Us</p>
        </div>
    </section>
    <section class="contact-container">
        <div class="contact-details">
            <h2>We’d love to hear from you</h2>
            
            <div class="details-container">
                <div class="detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Place</h3>
                        <p>Shop No. 5, Near City Hospital, Gandhi Road, Bhavnagar, Gujarat 364001</p>
                    </div>
                </div>
                <div class="detail">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Call Us</h3>
                        <p>+1555.987.6543<br>+1555.987.6541</p>
                    </div>
                </div>
                <div class="detail">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email Us</h3>
                        <p>epharmacy.medicalbvn@gmail.com</p>
                    </div>
                </div>
            </div>
            <div class="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2436.7860141090814!2d-0.1195437842342187!3d51.50318687963482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sLondon+Eye!5e0!3m2!1sen!2suk!4v1616161616161" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        <div class="contact-form">
            <h2>Send us a message</h2>
            <p>Class dolor et facilisi nibh taciti efficitur egestas.</p>
            <form>
                <input type="text" name="name" placeholder="Your Name">
                <input type="text" name="phone" placeholder="Phone">
                <input type="email" name="email" placeholder="Email Address">
                <input type="text" name="subject" placeholder="Subject">
                <textarea name="message" placeholder="Message"></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>


</body>

<?php include 'footer.html';?>


</html>
