# محسن موسوی میرکلایی: author website

A static, Persian-first (RTL) website showcasing the work of **Mohsen Mousavi Mirkalaei** (محسن موسوی میرکلایی). He is a poet, literary critic, journalist and stage actor (b. 1983, Babolsar; raised in Bushehr).

## Contents
- **About**: biography, with a portrait placeholder
- **Books**:
  - «جزیره‌نشین» (poems, Sarzamin-e Ahurai, 1395, Bamdad Poetry Books vol. 4)
  - «مقالات آتشی» (compiled & edited, Afraz, 2nd ed. 1400)
- **Criticism & writing**: Faratab review (1397), Arman columns (1391), Bushehr literary pages
- **Stage**, **Timeline**, **In the press**, **English summary**, **Contact**
- All facts link to their sources in the footer.

## Preview locally
```bash
python3 -m http.server 8000
```
Then open http://localhost:8000.

## Deploy (GitHub Pages)
Repository Settings → Pages → Source: *Deploy from a branch* → choose the branch and `/ (root)`.

## Placeholders to fill in
Search `index.html` for `PLACEHOLDER`:
1. **Selected poem** in the hero: lines from «جزیره‌نشین» chosen by the poet. No poem text was available online, so none was invented.
2. **Portrait**: add `assets/img/portrait.jpg` and swap in the `<img>` tag noted in the comment.
3. **Contact**: real email and social links (Instagram / Telegram / X).

## Structure
```
index.html
assets/css/style.css   design tokens, light/dark themes, responsive layout
assets/js/main.js      mobile nav, theme toggle, scroll reveal, active-section nav
assets/img/favicon.svg
```
