<div class="container py-5">
    <div class="text-center mb-5">
        <span class="badge bg-primary text-uppercase px-3 py-2 mb-2">Get in Touch</span>
        <h1 class="h2 fw-black text-dark mb-2">Contact Pande Computers</h1>
        <p class="text-muted">Our sales and technical specialists are ready to assist you in Kariakoo or via WhatsApp</p>
    </div>

    <div class="row g-4">
        <!-- Contact Cards -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-primary-subtle text-primary p-3 fs-4">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Physical Storefront</h6>
                        <p class="text-secondary small mb-1"><?= e($storeLocation ?? 'Ndanda & Aggrey Street, Kariakoo, Dar es Salaam, Tanzania') ?></p>
                        <span class="badge bg-light text-dark border">Walk-in Customers Welcome</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 p-4 mb-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-success-subtle text-success p-3 fs-4">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">WhatsApp Direct Orders</h6>
                        <p class="text-secondary small mb-2"><?= e($storePhone ?? '+255 787 311 342') ?></p>
                        <a href="https://wa.me/<?= e($storeWhatsApp ?? '255787311342') ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm fw-bold">
                            <i class="bi bi-whatsapp me-1"></i> Start WhatsApp Conversation
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 p-4 mb-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-info-subtle text-info p-3 fs-4">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Store Operating Hours</h6>
                        <ul class="list-unstyled small text-secondary mb-0">
                            <li><strong>Mon – Fri:</strong> 8:30 AM – 6:30 PM (EAT)</li>
                            <li><strong>Saturday:</strong> 8:30 AM – 5:30 PM (EAT)</li>
                            <li><strong>Sunday:</strong> Closed (Online WhatsApp active)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Inquiry Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 p-4 p-md-5">
                <h4 class="fw-bold text-dark mb-3">Send a Direct Inquiry</h4>
                <p class="text-muted small mb-4">Need a quotation for corporate procurement or have a question about device specifications? Fill out this form and we'll reply promptly.</p>

                <form onsubmit="alert('Thank you! Your message has been sent to our sales desk.'); return false;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Your Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required placeholder="Salim Rashid">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Phone / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required placeholder="+255 787 311 342">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address</label>
                        <input type="email" class="form-control" placeholder="name@example.com">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Message / Quotation Request <span class="text-danger">*</span></label>
                        <textarea rows="4" class="form-control" required placeholder="Specify hardware models, quantities, and delivery location..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2 shadow-sm">
                        <i class="bi bi-send-fill me-1"></i> Submit Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
