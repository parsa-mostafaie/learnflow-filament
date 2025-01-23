<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans dark:bg-gray-900 dark:text-white overflow-x-clip">
    <!-- Hero Section -->
    <header class="header-section text-white text-center py-20 container mx-auto dark:bg-gray-800">
        <h2 class="text-4xl font-bold mb-4">Welcome to Enlearn</h2>
        <p class="text-xl mb-6">The ultimate platform to learn English efficiently and effectively</p>
        <a href="{{route('login')}}" wire:navigate><x-primary-button type="button"><i class="fas fa-play me-2"></i> Get
                Started</x-primary-button></a>
    </header>

    <!-- About Section -->
    <section id="about" class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold mb-4">About Us</h2>
        <p>At Enlearn, we are dedicated to providing top-notch English learning resources tailored to your needs. Our
            platform is designed to be user-friendly, interactive, and engaging, ensuring you have the best learning
            experience possible.</p>
    </section>

    <!-- Features Section -->
    <section id="features" class="container mx-auto bg-gray-200 p-8 dark:bg-gray-700">
        <div>
            <h2 class="text-3xl font-semibold mb-4">Our Features</h2>
            <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded shadow scale-on-hover flex flex-col dark:bg-gray-800">
                    <h3 class="text-xl font-bold mb-2">Daily Streaks</h3>
                    <p class="mb-4">Maintain your learning momentum by achieving daily streaks. Stay consistent and see
                        your
                        progress soar!</p>
                    <div class="mt-auto text-center">
                        <a href="#" class="text-purple-700 hover:underline dark:text-purple-500">Learn More</a>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded shadow scale-on-hover flex flex-col dark:bg-gray-800">
                    <h3 class="text-xl font-bold mb-2">Leitner System</h3>
                    <p class="mb-4">Utilize the efficient Leitner system to ensure you retain and recall what you learn
                        effectively.
                    </p>
                    <div class="mt-auto text-center">
                        <a href="#" class="text-purple-700 hover:underline dark:text-purple-500">Learn More</a>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded shadow scale-on-hover flex flex-col dark:bg-gray-800">
                    <h3 class="text-xl font-bold mb-2">Interactive Exercises</h3>
                    <p class="mb-4">Engage with interactive exercises that adapt to your learning pace and style.</p>
                    <div class="mt-auto text-center">
                        <a href="#" class="text-purple-700 hover:underline dark:text-purple-500">Learn More</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold mb-4">Contact Us</h2>
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 gap-6">
            <form class="grid gap-6">
                <x-text-input type="text" placeholder="Your Name" class="input dark:bg-gray-700 dark:text-white" />
                <x-text-input type="email" placeholder="Your Email" class="input dark:bg-gray-700 dark:text-white" />
                <x-text-area placeholder="Your Message"
                    class="input h-32 dark:bg-gray-700 dark:text-white"></x-text-area>
                <x-primary-button type="submit" class="justify-center"><i class="fas fa-paper-plane me-2"></i> Send
                    Message</x-primary-button>
            </form>
            <div
                class="contact-info shadow-lg p-4 bg-[#f7fafc] rounded-lg md:rotate-[10deg] md:translate-x-4 md:translate-y-4 dark:bg-gray-800">
                <h3 class="text-xl font-bold mb-3">Contact with Developer</h3>
                <p>Or contact with the developer from:</p>
                <div class="flex gap-1 flex-wrap">
                    <x-primary-button type="submit"><i class="fab fa-linkedin me-2"></i>
                        <a href="https://www.linkedin.com/in/parsa-mostafaie">LinkedIn</a></x-primary-button>
                    <x-primary-button type="submit"><i class="fab fa-github me-2"></i> <a
                            href="https://github.com/parsa-mostafaie">GitHub</a></x-primary-button>
                    <x-primary-button type="submit"><i class="fa fa-phone me-2"></i> <a
                            href="tel:+989056372307">+989056372307</a></x-primary-button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer
        class="bg-purple-700 text-white p-4 text-center container mx-auto rounded-lg dark:bg-gray-800 dark:text-white">
        <p>&copy; 2025 Enlearn: English Learn. All rights reserved.</p>
    </footer>
</body>

</html>