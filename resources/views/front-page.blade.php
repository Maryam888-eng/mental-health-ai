<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mental Health Multi-AI Evaluation Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('front-page') }}">
            Mental Health AI Platform
        </a>

        <div class="d-flex gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                    Login
                </a>

                <a href="{{ route('register') }}" class="btn btn-primary">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-primary mb-3">
                    Academic Final Year Project
                </span>

                <h1 class="display-5 fw-bold mb-3">
                    Mental Health Multi-AI Evaluation Platform
                </h1>

                <p class="lead text-muted mb-4">
                    A secure academic platform where users receive AI-based mental wellness support,
                    doctors review AI responses, and administrators select the best-performing AI provider.
                </p>

                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            Get Started
                        </a>

                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">
                            Login
                        </a>
                    @endauth
                </div>

                <p class="small text-muted mt-3">
                    This platform is for academic demonstration and mental wellness support only.
                    It is not a replacement for professional medical treatment.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">System Flow</h5>

                        <div class="d-flex align-items-start mb-3">
                            <div class="badge bg-primary rounded-pill me-3">1</div>
                            <div>
                                <strong>User starts a conversation</strong>
                                <p class="text-muted mb-0 small">
                                    Users discuss stress, anxiety, loneliness, academic pressure, or general wellness.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="badge bg-primary rounded-pill me-3">2</div>
                            <div>
                                <strong>Active AI provider responds</strong>
                                <p class="text-muted mb-0 small">
                                    The system sends the message to the selected AI model using prompt engineering and conversation memory.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="badge bg-primary rounded-pill me-3">3</div>
                            <div>
                                <strong>Doctor reviews response quality</strong>
                                <p class="text-muted mb-0 small">
                                    Doctors score accuracy, empathy, safety, usefulness, and risk level.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <div class="badge bg-primary rounded-pill me-3">4</div>
                            <div>
                                <strong>Admin selects best provider</strong>
                                <p class="text-muted mb-0 small">
                                    Admin compares provider scores and activates the safest and most useful AI model.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-5">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold">For Users</h5>
                        <p class="text-muted">
                            Start private conversations, use suggested prompts, and receive supportive AI responses.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold">For Doctors</h5>
                        <p class="text-muted">
                            Review AI responses, score quality, flag risk, and recommend follow-up where needed.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold">For Admins</h5>
                        <p class="text-muted">
                            Manage providers, compare review scores, activate models, and monitor system performance.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-5">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">What Makes This Project Different?</h4>

                <div class="row g-4">
                    <div class="col-md-3">
                        <strong>Multi-AI Support</strong>
                        <p class="text-muted small mb-0">
                            Supports OpenRouter, Gemini, DeepSeek, and demo providers.
                        </p>
                    </div>

                    <div class="col-md-3">
                        <strong>Doctor Feedback</strong>
                        <p class="text-muted small mb-0">
                            Human experts evaluate AI responses for safety and quality.
                        </p>
                    </div>

                    <div class="col-md-3">
                        <strong>Provider Selection</strong>
                        <p class="text-muted small mb-0">
                            Admin activates providers based on measured performance.
                        </p>
                    </div>

                    <div class="col-md-3">
                        <strong>Future ML Training</strong>
                        <p class="text-muted small mb-0">
                            Doctor-reviewed data can be used to train a local quality evaluator model.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<footer class="py-4 bg-white border-top">
    <div class="container text-center text-muted small">
        Mental Health Multi-AI Evaluation Platform — Academic Project
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>