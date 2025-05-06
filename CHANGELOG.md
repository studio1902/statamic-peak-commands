# Changelog

## v9.0.0 (2025-05-06)

### What's new
- The ability to load in your own private blocks, sets and presets to install. #28 by @marcorieser
- The ability to compose blocks, sets and presets with custom operations. #28 by @marcorieser
- A lot of code refactor. #28 by @marcorieser

Thank you Marco Rieser for your amazing work on this.

### What's improved
- Business hours preset supports overnight spans. #31 by @porstendorfer
- The prompts presented during the `make:collection` regarding mounting are more clear. e6e64d79 by @robdekort
- The banner presets now uses `x-collapse`. f11c7f67 by @robdekort
- Accessibility and semantics in the FAQ preset. 0ce0b1b4 by @robdekort
- FAQ sections to close smoothly. #33 by @podrabinek
- Accessibility in the theme toggle preset. 6a6156a9 by @robdekort

### What's fixed
- The width of the block title in the clients preset. faab4a6e by @robdekort
- A comment in the clients preset notification. e411f1f8 by @robdekort
- A class used in the footer preset is now compatible with Tailwind 4. 6f77783f by @robdekort
- A typo in the business hours preset. #32 by @porstendorfer

## v8.16.1 (2025-01-22)

### What's fixed
- Fix arbitrary value in footer preset for Tailwind v4. a35deaec by @robdekort

## v8.16.0 (2025-01-22)

### What's new
- A mega menu preset. aa5566d7 by @robdekort

## v8.15.1 (2025-01-09)

### What's improved
- Fix double instructions. 8d38ff91 by @robdekort

## v8.15.0 (2025-01-09)

### What's improved
- Use prompt steps for the search preset instructions to improve clarity when installing the preset. 8b8b9d38 by @robdekort

## v8.14.0 (2025-01-06)

### What's improved
- Update theme toggle (dark mode) preset instructions for Tailwind v4. 91f034ff by @robdekort

## v8.13.0 (2024-12-18)

### What's changed
- Remove prompts dependency. 48e3551 by @robdekort

## v8.12.0 (2024-12-06)

### What's changed
- Limit Statamic dependency to v5. ddc0863 by @robdekort

## v8.11.2 (2024-11-09)

### What's fixed
- Actually move set partials to the correct folder when installing a Bard set. e20ab224 by @robdekort

## v8.11.1 (2024-11-09)

### What's fixed
- Move set partials to the correct folder when installing a Bard set. 6caaba3c by @robdekort

## v8.11.0 (2024-11-09)

### What's new
- An `install-set` command with a self hosted video set with responsive sources, controls, autoplay, loop, muted and more options. ad62c12d by @robdekort
- Move `Read more` preset to `install-set` command. ad62c12d by @robdekort

## v8.10.0 (2024-11-02)

### What's improved
- Update roles when installing banner preset. 11817bea by @robdekort

## v8.9.0 (2024-10-07)

### What's changed
- Remove vertical margin on set stub as we now use stacks to space them. 7d0ff66b by @robdekort

## v8.8.1 (2024-09-13)

### What's changed
- Remove microdata from the breadcrumbs preset to avoid collision with Peak's built in JSON-LD breadcrumbs. 733a55ae by @robdekort

## v8.8.0 (2024-09-11)

### What's new
- A `peak:make:nav` command to make a navigation. db885302 by @robdekort
- The ability to grant taxonomy permissions when running `peak:make:nav`. 6aa02a3d by @robdekort

## v8.7.0 (2024-09-11)

### What's new
- A mega footer preset with multiple navigations. 9eecc0ab by @robdekort

## v8.6.0 (2024-09-11)

### What's changed
- IMPORTANT: Use `make:*` signature instead of `add-*` in Peak make commands. fbc0867a by @robdekort

## v8.5.0 (2024-09-11)

### What's new
- A `make-global` command to make a global. 0f1ef008 by @robdekort
- A `make-taxonomy` command to make a taxonomy. 04912108 by @robdekort

### What's changed
- IMPORTANT: Use `make-*` signature instead of `add-*` in Peak make commands. 8692ea22 by @robdekort

## v8.4.4 (2024-09-04)

### What's improved
- Explicitely name a block when being asked to add it to a new or existing group. 385845cd by @robdekort

## v8.4.3 (2024-08-23)

### What's fixed
- On a multisite environment the feature matrix from the pricing tiers preset wouldn't show any features. b00aa441 by @robdekort

## v8.4.2 (2024-08-05)

### What's fixed
- Fix a tpyo that prevents the vacancies preset to install. 0b59c4fd by @robdekort

## v8.4.1 (2024-07-29)

### What's improved
- Update Pricing tiers install instructions. a88c0ff0 by @robdekort

## v8.4.0 (2024-07-04)

### What's new
- Add singular names to the presets with type "rename". #25 by @sytheveenje

## v8.3.2 (2024-06-18)

### What's improved
- Improve a11y for clients preset. 625631df by @robdekort

## v8.3.1 (2024-05-29)

### What's improved
- Add button labels to replicators. 192437dc by @robdekort

## v8.3.0 (2024-04-25)

### What's new
- Adding article sets is more resilient when the article fieldset has more fields. cf98e5b0 by @robdekort

## v8.2.0 (2024-04-19)

### What's new
- An installable Read more preset to add a related article to Bard. 9f89e420 by @robdekort

## v8.1.0 (2024-04-19)

### What's new
- Use multisearch for installing presets and blocks. 4cf11290 by @robdekort
- Add an option to skip certain files when they already exist. b770a23b by @robdekort
- Add a pause step when showing instructions. 5c980570 by @robdekort

## v8.0.0 (2024-04-19)

### What's new
- Support Statamic v5 and prefix SVG tag attributes. #23 by @robdekort

### What's improved
- Use disabled variant in search form. b403339e by @robdekort
- Close theme toggle upon selecting a theme. 8ac7b637 by @robdekort

## v7.5.0 (2024-04-10)

### What's improved
- Add back banner preset and improve a11y. 10e23ac6 by @robdekort

## v7.4.2 (2024-04-03)

### What's fixed
- Fix issue when creating new Article/Page builder groups. c2fff61d by @marcorieser

## v7.4.1 (2024-04-03)

### What's improved
- Update roles when installing the pricing preset. b62afa2a by @robdekort

## v7.4.0 (2024-04-03)

### What's new
- A pricing preset to display pricing tiers and a feature table matrix. 3f9ad8fe by @robdekort
- An option to create page builder groups when adding blocks. 2b8143d7 by @robdekort
- An option to create article/bard groups when adding sets. ab25aafa by @robdekort
- The ability to run commands as part of presets. 83a2fbee by @robdekort

### What's improved
- Bigger select and search prompts. 7194721e by @robdekort
- Fix layout issue in FAQ preset when quesiton heading lines wrap. 925577f8 by @robdekort

## v7.3.0 (2024-03-17)

### What's new
- A banner preset that sits on top of your site users can click away. f1df4291 by @robdekort

### What's improved
- Simplified FAQ preset. Open state is not exclusive anymore. The first item is opened by default. f4df7680 by @robdekort

## v7.2.0 (2024-03-09)

### What's new
- An option to disable slugs when running `peak:add-collection`. 3055b187 by @robdekort

## v7.1.0 (2024-03-06)

### What's new
- When mounting a collection on a page using `peak:add-collection`, you can now also search for a page to mount it on. f7a9179a by @robdekort

## v7.0.1 (2024-03-05)

### What's improved
- Only check for a valid license when it's actually needed. e05356ff by @robdekort

## v7.0.0 (2024-03-01)

### What's new
- Remove focus rings and use outlines. Follow along with [this PR](https://github.com/studio1902/statamic-peak/pull/384) if you want to update your site. #22 by @robdekort

## v6.1.0 (2024-02-25)

### What's new
- Add widgets instruction after running`peak:add-collection`. 54f7ed86 by @robdekort

### What's improved
- Extend block partials in index content stubs, as opposed to the index partials themselves. This way you can use optional index content fields. 5b7de500 by @robdekort

## v6.0.3 (2024-02-20)

### What's changed
- Change license alert styling. 0139a58b by @robdekort

## v6.0.2 (2024-02-19)

### What's fixed
- Actually check for a valid license. 0139a58b by @robdekort

## v6.0.1 (2024-02-19)

### What's fixed
- Add back missing stub for the `clear-site` command. c3421a46 by @robdekort

## v6.0.0 (2024-02-19)

### What's new
- To fund ongoing development of Peak, I've decided to make this supplemental addon a paid product. To continue using it you need a valid license for it to work. Thanks for your support.

### What's improved
- Add excempt toggle to images blueprint in the Image credits preset. 63a4bbe1 by @robdekort.

## v5.0.2 (2024-02-11)

### What's fixed
- Typo in events show stub. 46b40bd8 by @robdekort

## v5.0.1 (2024-02-11)

### What's fixed
- Events show stub on mobile. 45b20911 by @robdekort

## v5.0.0 (2024-01-26)

### What's new
- Update page builder stubs to extend block partial. 4bf9d113 by @robdekort

## v4.4.0 (2024-01-26)

### What's new
- Use easier date formatting in JSON-ld sn snippets. 3f9ac1aa by @robdekort
- Automatically fix date formatting on existing sites. 22a3328d by @robdekort

## v4.3.0 (2024-01-26)

### What's new
- Let's fix those RSS feeds automatically with an update script. It'll save us both time. cb911645 by @robdekort

## v4.2.1 (2024-01-26)

### What's fixed
- Use proper date formatting and crop asset in RSS feed. 0c7e5c1c by @robdekort

## v4.2.0 (2024-01-21)

### What's improved
- Make some more stubs compatible with the fluid grid. bb30f29e by @robdekort

## v4.1.0 (2024-01-18)

### What's new
- Make the cards block more accessible. 9b5504a5 by @robdekort
- Update icon for the columns block. 9b5504a5 by @robdekort

## v4.0.0 (2024-01-05)

### What's new
- Support for the [`fluid-grid`](https://github.com/studio1902/statamic-peak/pull/373) component in all stubs. #20 by @robdekort

## v3.2.0 (2023-12-19)

### What's improved
- Return the first icon on non WSL Windows and skip the Laravel Search Prompt. 4a15f77d by @robdekort
- Use button attributes from Tools addon in cards stub. 0d488b6a by @robdekort

## v3.1.0 (2023-12-05)

### What's new
- Add instructions and icon prompts to `add-set` command. dec5ba0b by @robdekort

### What's improved
- Add `py-8` to `add-set` Antlers stub. 7c924d52 by @robdekort

## v3.0.0 (2023-12-01)

### What's new
- Replace `outer-grid` with `stack` utilities in all stubs. See [this PR](https://github.com/studio1902/statamic-peak/pull/363) for more information. 7a631585 by @robdekort

## v2.7.0 (2023-11-17)

### What's new
- Support [custom icons](https://github.com/statamic/cms/pull/8931) for Replicators and Bards when running `php please peak:add-block`. 7a631585 by @robdekort

## v2.6.3 (2023-11-16)

### What's improved
- Use fuzzy search for icons when running `add-block`. 42f2b882 by @robdekort

## v2.6.2 (2023-11-06)

### What's improved
- Use Prompts Suggest for icons when running `add-block`. 3f2d4256 by @robdekort

## v2.6.1 (2023-11-06)

### What's new
- Support icons for `add-block`, `add-collection`, `install-preset`, and `install-block`. 7cc48d9a by @robdekort

### What's fixed
- Added missing `index_content` to page builder for the Vacancies preset. 7cc48d9a by @robdekort

## v2.6.0 (2023-10-13)

### What's improved
- Remove date field from non dated collection blueprints. f62c3549 by @robdekort
- Curly quotes in search string. 271807d5 by @robdekort
- Improve search preset accessibility. 2e3afb17 and 2e3afb17 by @robdekort

## v2.5.2 (2023-09-11)

### What's improved
- Change closing conditional and remove duplicate class in news stubs. #19 by @hgrimelid

## v2.5 (2023-08-30)

### What's new
- Use replicators in link blocks and columns instead of grids. 8b150b37 by @robdekort

## v2.4 (2023-08-30)

### What's new
- Add start/end time feature to events preset. 9af5dbdd by @robdekort

## v2.3.4 (2023-08-29)

### What's fixed
- Issue when installing multiple presets at once. 7a512f04 by @robdekort

## v2.3.3 (2023-08-14)

### What's improved
- Validate preset and block installer multiselects. #18 by @mikemartin

## v2.3.2 (2023-08-08)

### What's improved
- Use text() prompts in preset rename operation. e38f2a7e by @robdekort

## v2.3.1 (2023-08-05)

### What's improved
- Improve Install Block and Install Preset command UI. 10a78768 by @robdekort

## v2.3 (2023-08-04)

### What's new
- Use Laravel Prompts. e7771296 by @robdekort

## v2.2 (2023-08-04)

### What's improved
- Update aspect ratio markup in a few stubs. a19edc39 by @robdekort

## v2.1 (2023-05-11)

### What's changed
- Delete redundant section instructions from preset stubs. 5d699849 by @robdekort

## v2.0 (2023-05-09)

**Breaking changes**: If you upgrade an existing site make sure to apply the [changes made to Peak Core in v12](https://github.com/studio1902/statamic-peak/releases/tag/v12.0).

### What's new
- Statamic v4 support including group support when creating/installing page builder blocks and/or sets and all stub blueprints have been updated to use the new blueprint sections. #12 by @robdekort
- Add snippet option to `add-partial` command. #12 by @robdekort
- Add option for a different filename to `add-block` command. #12 by @robdekort
- Add morphing Live Preview support to preset collections. #12 by @robdekort

### What's fixed
- Update `link_type` with prefix `sign_up_` in Events preset stub. #14 by @bygstudio

## v1.27 (2023-03-23)

### What's new
- Add an image credits presets that lists all images with copyright information. 0816a4a0 by @robdekort

## v1.26 (2023-03-23)

### What's fixed
- Properly render plus/minus in FAQ based on state. 1e7dde24 by @robdekort

## v1.25 (2023-03-18)

### What's improved
- Use partial tag method. 494ba1a1 by @robdekort

## v1.24 (2023-03-11)

### What's improved
- Remove hard coded path in news stub comment #10 by @hgrimelid
- Convert tabs to spaces in template stubs #11 by @hgrimelid

## v1.23 (2023-02-21)

### What's new
- Add vacancies preset with a collection, mounted entry, index and show templates including jSON-ld support. #8 by @robdekort

### What's improved
- Limit related news articles to 3 instead of 1 by default. b9344562 by @robdekort

## v1.22 (2023-02-21)

### What's improved
- Make feed route notification dynamic for the news preset. cc08bdf0 by @robdekort

## v1.21 (2023-02-10)

### What's new
- Make stubs publishable. #7 by @marcorieser

## v1.20 (2023-02-09)

### What's new
- Use new search snippets in search preset. f81bbf1c by @marcorieser

## v1.19 (2023-02-09)

### What's new
- Changes for addonification #5 by @marcorieser

## v1.18 (2023-02-07)

### What's changed
- Move namespace from `Studio1902\Peak` to `Studio1902\PeakCommands`. #2 by @marcorieser
