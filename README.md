# 👵 ElderCare SG — Compassionate Elderly Daycare Platform

Where compassionate care meets modern comfort for every family.

ElderCare SG is a modern, accessible, and emotionally thoughtful web platform designed for families in Singapore seeking trusted elderly daycare services. Built with Laravel, TailwindCSS, and a design[...]

![hero-screenshot-placeholder](public/assets/images/hero-placeholder.jpg)

🔗 Live demo coming soon

---

## 💡 Vision & Mission

We envision a digital experience that:

- Communicates warmth, reliability, and clinical trustworthiness
- Helps adult children confidently book care services for their aging parents
- Brings accessibility and performance best practices into eldercare

🎯 Audience: Families of elderly Singaporeans (60+), caregivers, clinicians  
🛠️ Platform: Mobile-friendly web app

---

## 🌐 Project Architecture & Stack

| Layer            | Tech                        |
|------------------|-----------------------------|
| Backend          | Laravel 12 (PHP 8.2)        |
| Frontend         | Blade Templates + TailwindCSS + Alpine.js |
| Database         | MariaDB                     |
| Caching/Queues   | Redis (jobs, notifications) |
| Dev Environment  | Docker                      |
| CI/CD            | GitHub Actions              |

📁 MVC structure with service layer:  
app/Http, app/Models, app/Services, app/Jobs, app/Support

---

## 🎨 Design-First Experience

This project is crafted using a UI/UX-first approach, where visuals and accessibility are core to success.

📄 Read the full Project Requirements Document (PRD) → [PRD.md](./docs/PRD.md)

✨ Visual Language:

- Color palette: deep blues, warm ambers, calming greens
- Typography: Playfair Display (serif), Inter (modern sans-serif)
- Motion: micro-interactions, fade-ins, hover effects (prefers-reduced-motion respected)

🖼️ Uses licensed lifestyle photography to convey care and dignity.

---

## 🚀 Getting Started

Clone and run the project locally using Docker.

```bash
git clone https://github.com/nordeim/ElderCare-SG.git
cd ElderCare-SG
cp .env.example .env
./vendor/bin/sail up -d
php artisan migrate
npm install && npm run dev
```

Visit http://localhost to view the app locally.

### 🧪 QA & Accessibility

This project adheres to WCAG 2.1 AA standards.

- ✅ Color contrast checked
- ✅ Keyboard navigability for all components
- ✅ Lightbox, carousel, nav are screen reader accessible
- ✅ prefers-reduced-motion respected
- ✅ Lighthouse score target: >90 on mobile and desktop

Test tools:

- axe-core (browser extension or CI pipeline)
- Lighthouse (Chrome DevTools)
- VoiceOver (Mac) and NVDA (Windows)

### 🛠️ Developer Tooling

Common scripts:

| Command | Purpose |
|---------|---------|
| npm run dev | Compile Tailwind + watch |
| php artisan migrate | Run DB migrations |
| sail artisan test | Run test suite (if added) |
| npm run build | Build assets for production |
| ./vendor/bin/sail up | Start Laravel environment |

### 🧩 Components & UI System

Built using TailwindCSS + shadcn components:

- <Card> → Program Highlight Blocks
- <Carousel> → Testimonials Marquee
- <Steps> → Care Philosophy Timeline
- <Input> & <Button> → Newsletter Signup

Reusable layout partials:  
layouts/header.blade.php, layouts/footer.blade.php, components/cta-card.blade.php

---

## 🤝 Contributing

We welcome thoughtful contributions — accessibility, performance, UI polish, or feature ideas!

- Fork this repo
- Create a new branch: feat/your-feature-name
- Follow Laravel’s PSR-12 + Tailwind class naming conventions
- Submit a pull request with a clear description

Check our [CONTRIBUTING.md](./CONTRIBUTING.md) for full details (coming soon).

---

## 📄 License & Acknowledgements

MIT License © 2025 Nordeim

Credits:

- Icons: Lucide (https://lucide.dev)
- Fonts: Google Fonts (Playfair Display, Inter)
- Photos: Licensed via [unsplash.com/@...]
- Carousel: Embla or shadcn/ui carousel

📬 Feedback & Contact

Have ideas, found bugs, or just want to say hi?

Open an issue on GitHub → https://github.com/nordeim/ElderCare-SG/issues

Or contact: hello@nordeim.sg (if applicable)

—

---

## 🖼 Screenshots

Here’s a preview of what the ElderCare SG experience will look like:

| Landing Page Hero | Program Cards | Testimonials Carousel |
|-------------------|---------------|------------------------|
| ![hero](./docs/screens/hero.png) | ![cards](./docs/screens/programs.png) | ![carousel](./docs/screens/testimonials.png) |

> Screenshots above are mockups. Final UI may vary based on real content.

---

## 🔍 Related Documentation

- 📄 Full UI/UX PRD → /docs/PRD.md  
- 🎨 Style Guide → /docs/design-system.md (Coming soon)  
- 🧰 Architecture Guide → /docs/architecture.md (Coming soon)  
- ✅ Accessibility Checklist → /docs/accessibility.md (Coming soon)  

---

## 🔒 Security Policy

If you discover a vulnerability or have concerns related to elder data protection or accessibility compliance:

- Please report privately to hello@nordeim.sg  
- Responsible disclosure is appreciated  
- No personal data is collected in this MVP

---

## 📦 Deployment & Hosting (Planned)

This site is designed to be deployable via:

- Laravel Forge (for managed hosting)
- Vercel or Netlify for static Blade-rendered pages
- Docker image for self-hosted deployment

Coming soon:

- ✅ CI/CD with GitHub Actions  
- ✅ Preview deploys via GitHub PRs  
- ✅ Lighthouse test automation  

---

## ❤️ Acknowledgements

This project is inspired by:

- Families caring for aging loved ones  
- UI/UX designers committed to accessibility  
- Open-source tools like Tailwind, Laravel, and Alpine.js  
- Web performance advocates pushing for fast, inclusive design  

---

## 🙏 Final Note

This project is a love letter to families, caregivers, and seniors in Singapore. We believe design, care, and technology can — and should — coexist gracefully.

If you're reading this and have thoughts, feedback, or want to help, we welcome you.

🔗 GitHub Repo: https://github.com/nordeim/ElderCare-SG  
📧 Contact: hello@nordeim.sg (or open an Issue)  
🛠️ Contribute: Fork, branch, improve!

—
👴 Built with care and code · 🇸🇬 Made for Singapore