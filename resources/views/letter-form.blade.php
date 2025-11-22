@extends('layout')

@section('title', 'Send Your Letter to Santa - Letters2Santa')

@section('extra-css')
    <!-- International Telephone Input CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
    <style>
        .iti {
            width: 100%;
        }

        .iti__flag-container {
            padding-right: 12px;
        }

        .tier-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .tier-badge.free {
            background: linear-gradient(135deg, var(--christmas-green), #0d472a);
            color: white;
        }

        .tier-badge.premium {
            background: linear-gradient(135deg, var(--christmas-red), #a01828);
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        @if(request('tier') === 'free')
                            <span class="tier-badge free">✨ FREE BASIC TIER</span>
                        @elseif(request('tier') === 'premium')
                            <span class="tier-badge premium">⭐ PREMIUM TIER</span>
                        @endif

                        <h1 class="santa-font" style="color: var(--christmas-red); font-size: 3rem;">
                            <i class="fas fa-envelope-open-text"></i> Write to Santa
                        </h1>
                        <p class="lead text-muted">
                            Fill in the magical form below and let Santa know your child's Christmas wishes!
                        </p>
                    </div>

                    <form method="POST" action="{{ route('letter.store') }}" id="letterForm" enctype="multipart/form-data">
                        @csrf

                        <!-- Hidden tier field -->
                        <input type="hidden" name="tier" value="{{ request('tier', 'premium') }}">

                        <!-- Child's Information -->
                        <div class="mb-4">
                            <h3 class="santa-font" style="color: var(--christmas-green);">
                                <i class="fas fa-child"></i> About Your Child
                            </h3>
                        </div>

                        <div class="mb-4">
                            <label for="child_name" class="form-label fw-bold">
                                <i class="fas fa-user"></i> Child's Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg @error('child_name') is-invalid @enderror" id="child_name" name="child_name" value="{{ old('child_name') }}"
                                placeholder="e.g., Emma, Jack, Sophie..." required>
                            @error('child_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                This name will appear in the personalised storybook
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="age_range" class="form-label fw-bold">
                                <i class="fas fa-birthday-cake"></i> Age Range <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg @error('age_range') is-invalid @enderror" id="age_range" name="age_range" required>
                                <option value="">Select age range...</option>
                                <option value="3-5" {{ old('age_range') == '3-5' ? 'selected' : '' }}>3-5 years old</option>
                                <option value="6-8" {{ old('age_range') == '6-8' ? 'selected' : '' }}>6-8 years old</option>
                                <option value="9-12" {{ old('age_range') == '9-12' ? 'selected' : '' }}>9-12 years old</option>
                            </select>
                            @error('age_range')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                We'll create an age-appropriate story just for them
                            </small>
                        </div>

                        <div class="mb-5">
                            <label for="message_to_santa" class="form-label fw-bold">
                                <i class="fas fa-comment-dots"></i> Message to Santa <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('message_to_santa') is-invalid @enderror" id="message_to_santa" name="message_to_santa" rows="6"
                                placeholder="Dear Santa,&#10;&#10;This year I have been very good! I would love...&#10;&#10;Love, [Child's Name]" maxlength="1000" required>{{ old('message_to_santa') }}</textarea>
                            @error('message_to_santa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <span id="charCount">0</span>/1000 characters
                            </small>
                        </div>

                        <!-- Photo Upload - Only for PREMIUM tier -->
                        @if(request('tier') === 'premium')
                        <div class="mb-5">
                            <label for="child_photo" class="form-label fw-bold">
                                <i class="fas fa-camera"></i> Child's Photo <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control @error('child_photo') is-invalid @enderror" id="child_photo" name="child_photo" accept="image/jpeg,image/jpg,image/png" required>
                            @error('child_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-magic"></i> Upload a photo and we'll create a <strong>cartoon version</strong> of your child to appear in the story!<br>
                                <i class="fas fa-shield-alt"></i> <strong>100% Secure:</strong> Photo is automatically deleted after 7 days.<br>
                                <i class="fas fa-info-circle"></i> Accepted formats: JPG, PNG (Max 5MB)
                            </small>
                        </div>
                        @endif

                        <!-- Timezone Selection -->
                        <div class="mb-5">
                            <label for="timezone" class="form-label fw-bold">
                                <i class="fas fa-clock"></i> Your Timezone <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-select form-select-lg @error('timezone') is-invalid @enderror"
                                id="timezone"
                                name="timezone"
                                required
                            >
                                <option value="">Detecting your timezone...</option>
                            </select>
                            @error('timezone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Used to deliver your storybook at the right time on Christmas morning
                            </small>
                        </div>

                        <!-- Parent's Information -->
                        <div class="mb-4">
                            <h3 class="santa-font" style="color: var(--christmas-green);">
                                <i class="fas fa-user-circle"></i> Parent/Guardian Information
                            </h3>
                            <p class="text-muted">
                                <small>
                                    <i class="fas fa-info-circle"></i>
                                    We need your details to send the magical storybook
                                </small>
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="parent_name" class="form-label fw-bold">
                                <i class="fas fa-user"></i> Your Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg @error('parent_name') is-invalid @enderror" id="parent_name" name="parent_name" value="{{ old('parent_name') }}"
                                placeholder="Your full name" required>
                            @error('parent_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="parent_email" class="form-label fw-bold">
                                <i class="fas fa-envelope"></i> Your Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control form-control-lg @error('parent_email') is-invalid @enderror" id="parent_email" name="parent_email" value="{{ old('parent_email') }}"
                                placeholder="your.email@example.com" required>
                            @error('parent_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                We'll send a confirmation and your e-book here
                            </small>
                        </div>

                        <div class="mb-5">
                            <label for="parent_mobile" class="form-label fw-bold">
                                <i class="fas fa-mobile-alt"></i> Your Mobile Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control form-control-lg @error('parent_mobile') is-invalid @enderror" id="parent_mobile" name="parent_mobile" value="{{ old('parent_mobile') }}"
                                placeholder="Enter your mobile number" required>
                            @error('parent_mobile')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Country detected automatically - you can change it if needed
                            </small>
                        </div>

                        <!-- Privacy & Terms -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-shield-alt"></i>
                            <strong>Privacy Promise:</strong> Your information is secure and will only be used to deliver your child's magical storybook.
                            We never share your details with third parties. Read our <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a>.
                        </div>

                        <!-- Payment Summary - Dynamic based on tier -->
                        <div class="card mb-4" style="background: linear-gradient(135deg, #fff8dc, #ffffff);">
                            <div class="card-body p-4">
                                <h4 class="santa-font mb-3" style="color: var(--christmas-red);">
                                    <i class="fas fa-gift"></i> Your Magical Package
                                </h4>
                                <ul class="list-unstyled mb-3">
                                    <li><i class="fas fa-check text-success"></i> Personalised Christmas E-Book</li>
                                    <li><i class="fas fa-check text-success"></i> Letter from Santa</li>
                                    @if(request('tier') === 'premium')
                                        <li><i class="fas fa-check text-success"></i> Cartoon Photo of Your Child</li>
                                        <li><i class="fas fa-check text-success"></i> Printable Certificate</li>
                                        <li><i class="fas fa-check text-success"></i> Premium Story Version</li>
                                    @endif
                                </ul>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0">Total:</span>
                                    @if(request('tier') === 'free')
                                        <span class="h3 mb-0 santa-font" style="color: var(--christmas-green);">
                                            FREE
                                        </span>
                                    @else
                                        <span class="h3 mb-0 santa-font" style="color: var(--christmas-red);">
                                            $4.99 AUD
                                        </span>
                                    @endif
                                </div>
                                @if(request('tier') === 'premium')
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            Black Friday Special - Regular price $9.49
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="accept_terms" name="accept_terms" required>
                            <label class="form-check-label" for="accept_terms">
                                I agree to the <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a> and <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a> <span
                                    class="text-danger">*</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            @if(request('tier') === 'free')
                                <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, var(--christmas-green), #0d472a); color: white; border-radius: 50px;">
                                    <i class="fas fa-gift"></i> Get My FREE E-Book
                                </button>
                            @else
                                <button type="submit" class="btn btn-christmas btn-lg">
                                    <i class="fas fa-lock"></i> Proceed to Secure Payment - $4.99
                                </button>
                            @endif
                        </div>

                        <div class="text-center mt-3">
                            @if(request('tier') === 'premium')
                                <small class="text-muted">
                                    <i class="fas fa-credit-card"></i>
                                    Secure payment powered by Stripe
                                </small>
                            @else
                                <small class="text-muted">
                                    <i class="fas fa-gift"></i>
                                    No payment required • Delivered Christmas Day
                                </small>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="row mt-4">
                <div class="col-md-4 text-center mb-3">
                    <i class="fas fa-lock" style="font-size: 2rem; color: white;"></i>
                    <p class="text-white mt-2 mb-0"><small><strong>Secure {{ request('tier') === 'premium' ? 'Payment' : 'Process' }}</strong></small></p>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="fas fa-shield-alt" style="font-size: 2rem; color: white;"></i>
                    <p class="text-white mt-2 mb-0"><small><strong>Privacy Protected</strong></small></p>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <i class="fas fa-heart" style="font-size: 2rem; color: white;"></i>
                    <p class="text-white mt-2 mb-0"><small><strong>Christmas Magic</strong></small></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <!-- International Telephone Input JS -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>

    <!-- Moment.js (required base library) -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <!-- Moment Timezone JS -->
    <script src="https://cdn.jsdelivr.net/npm/moment-timezone@0.5.43/builds/moment-timezone-with-data.min.js"></script>

    <script>
        // Get tier from form
        const tier = document.querySelector('input[name="tier"]').value;

        // Timezone initialization - wait for moment to load
        function initializeTimezones() {
            // Check if moment is loaded
            if (typeof moment === 'undefined' || !moment.tz) {
                setTimeout(initializeTimezones, 100);
                return;
            }

            const timezoneSelect = document.getElementById('timezone');
            const userTimezone = moment.tz.guess();

            // Get all timezones
            const timezones = moment.tz.names();

            // Popular timezones first
            const popularTimezones = [
                'Australia/Sydney',
                'Australia/Melbourne',
                'Australia/Brisbane',
                'America/New_York',
                'America/Los_Angeles',
                'America/Chicago',
                'Europe/London',
                'Europe/Paris',
                'Asia/Singapore',
                'Asia/Tokyo'
            ];

            // Clear loading option
            timezoneSelect.innerHTML = '';

            // Add popular timezones
            const popularGroup = document.createElement('optgroup');
            popularGroup.label = 'Popular Timezones';
            popularTimezones.forEach(tz => {
                const option = document.createElement('option');
                option.value = tz;
                option.textContent = tz.replace(/_/g, ' ') + ' (' + moment.tz(tz).format('z') + ')';
                if (tz === userTimezone) {
                    option.selected = true;
                }
                popularGroup.appendChild(option);
            });
            timezoneSelect.appendChild(popularGroup);

            // Add all timezones
            const allGroup = document.createElement('optgroup');
            allGroup.label = 'All Timezones';
            timezones.forEach(tz => {
                if (!popularTimezones.includes(tz)) {
                    const option = document.createElement('option');
                    option.value = tz;
                    option.textContent = tz.replace(/_/g, ' ');
                    if (tz === userTimezone) {
                        option.selected = true;
                    }
                    allGroup.appendChild(option);
                }
            });
            timezoneSelect.appendChild(allGroup);
        }

        // Start initialization when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeTimezones);
        } else {
            initializeTimezones();
        }

        // Initialize intl-tel-input
        const phoneInput = document.querySelector("#parent_mobile");
        const iti = window.intlTelInput(phoneInput, {
            // Auto-detect country by IP (free lookup)
            initialCountry: "auto",
            geoIpLookup: function(success, failure) {
                // Use ipapi.co free API (1000 requests/day, no key required)
                fetch('https://ipapi.co/json/')
                    .then(response => response.json())
                    .then(data => success(data.country_code))
                    .catch(() => success('au')); // Default to Australia if lookup fails
            },

            // Options
            preferredCountries: ['au', 'us', 'gb', 'nz', 'ca'], // Show these at top
            separateDialCode: true, // Show +61 separately
            nationalMode: false, // Show full international number
            formatOnDisplay: true, // Auto-format as user types
            autoPlaceholder: "aggressive", // Show example format

            // Utility script for formatting/validation
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js"
        });

        // On form submit, get the full international number
        document.getElementById('letterForm').addEventListener('submit', function(e) {
            // Get full international number with country code
            const fullNumber = iti.getNumber();

            // Validate the number
            if (!iti.isValidNumber()) {
                e.preventDefault();
                phoneInput.classList.add('is-invalid');

                // Show error message
                let errorMsg = phoneInput.parentElement.querySelector('.invalid-feedback');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback d-block';
                    phoneInput.parentElement.appendChild(errorMsg);
                }
                errorMsg.textContent = 'Please enter a valid mobile number for your country';

                // Scroll to phone field
                phoneInput.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return false;
            }

            // Set the full international number in the field
            phoneInput.value = fullNumber;
            phoneInput.classList.remove('is-invalid');
        });

        // Remove validation error on input
        phoneInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorMsg = this.parentElement.querySelector('.invalid-feedback');
            if (errorMsg) {
                errorMsg.remove();
            }
        });

        // Character counter for message
        document.getElementById('message_to_santa').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('charCount').textContent = count;
        });

        // Update initial count if there's old input
        window.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('message_to_santa');
            if (textarea.value) {
                document.getElementById('charCount').textContent = textarea.value.length;
            }
        });

        // Photo preview - only if premium tier
        @if(request('tier') === 'premium')
        document.getElementById('child_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];

            // Remove existing preview if any
            const existingPreview = document.getElementById('photoPreview');
            if (existingPreview) {
                existingPreview.remove();
            }

            if (file) {
                // Check file size (5MB = 5242880 bytes)
                if (file.size > 5242880) {
                    alert('File size must be less than 5MB');
                    this.value = '';
                    return;
                }

                // Check file type
                if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                    alert('Only JPG and PNG files are allowed');
                    this.value = '';
                    return;
                }

                // Create preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewDiv = document.createElement('div');
                    previewDiv.id = 'photoPreview';
                    previewDiv.className = 'mt-3 p-3';
                    previewDiv.style.cssText = 'background: #f8f9fa; border-radius: 12px; border: 2px dashed var(--christmas-green);';

                    previewDiv.innerHTML = `
                    <div class="text-center">
                        <p class="mb-2"><strong style="color: var(--christmas-green);"><i class="fas fa-check-circle"></i> Photo Uploaded Successfully!</strong></p>
                        <img src="${event.target.result}" alt="Child Photo Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <p class="mt-2 mb-0 text-muted"><small><i class="fas fa-magic"></i> We'll create a cartoon version for the story!</small></p>
                    </div>
                `;

                    document.getElementById('child_photo').parentElement.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            }
        });
        @endif
    </script>
@endsection
