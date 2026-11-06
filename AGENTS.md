# AI Unit website change workflow

This repository is the source of truth for the PHP website published at https://aim.govmu.org/aim/.

1. Start from the latest `main` and create a feature branch. Commit all website code and asset changes to GitHub and open a pull request.
2. Run the exact pull request commit in a local browser preview on the laptop before review. Run `php -S 127.0.0.1:5600 -t public router.php` from a clean checkout and open `http://127.0.0.1:5600/`. Use separate test configuration, data and credentials. Follow the README for Composer and media setup. Record any production-specific checks the laptop cannot cover. When an isolated IIS staging environment is available, use it to verify IIS rules, the production URL prefix and deployment-specific behaviour.
3. Record the preview location or reproduction command, tested commit, and functional, responsive and accessibility results in the pull request. Review and approve the local preview before merging to main.
4. For an authorized release, deploy only a reviewed GitHub commit that passed preview. Keep the previous release available for rollback. Make all follow-up changes through a new branch, pull request and preview. Direct code edits on the production VM are prohibited.

Production environment values, IIS access restrictions, credentials, TLS material, uploads and runtime data are maintained outside the public repository. Preserve them across releases. The local Apache deployment script is for development.
