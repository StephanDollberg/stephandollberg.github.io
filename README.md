# dollberg.xyz

This repository now builds the blog with Astro and deploys it to GitHub Pages through GitHub Actions.

## Local development

```bash
npm install
npm run dev
```

Build the production site locally with:

```bash
npm run build
```

## GitHub Pages setup

1. Push this repository to GitHub.
2. In GitHub, open `Settings -> Pages`.
3. Under `Build and deployment`, set `Source` to `GitHub Actions`.
4. Wait for the `Deploy to GitHub Pages` workflow to finish.
5. In `Settings -> Pages`, set the custom domain to `dollberg.xyz` if it is not already set.
6. Enable `Enforce HTTPS` once GitHub finishes issuing the certificate.

The deployment workflow is in `.github/workflows/deploy.yml`. The custom domain file is `public/CNAME`.

## DNS

For `dollberg.xyz`, point the domain at GitHub Pages using either:

- `ALIAS` or `ANAME` for `@` -> `stephandollberg.github.io`
- or GitHub Pages apex `A` and `AAAA` records, plus `www` as a `CNAME` to `stephandollberg.github.io`

## Notes

- `src/` contains the Astro site.
- `public/` contains static assets copied straight through to the deployed site.
