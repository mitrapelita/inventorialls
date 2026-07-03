# Advanced Vibecode Installation Guide

## 1. Install RTK (Rust Token Killer)
- **Repository:** [rtk-ai/rtk](https://github.com/rtk-ai/rtk)
- **Description:** A CLI proxy to reduce token usage for AI coding assistants.
- **Command:**
  ```bash
  winget install -e --id rtk-ai.rtk
  ```
  *(Then initialize it globally with `rtk init -g`)*

## 2. Install Caveman
- **Repository:** [JuliusBrussee/caveman](https://github.com/JuliusBrussee/caveman.git)
- **Description:** Reduces token consumption by stripping filler from AI agent responses.
- **Command:**
  ```bash
  npm install -g @juliusbrussee/caveman-code
  ```

## 3. Install UIUX Promax
- **Description:** Design intelligence skill to provide professional-grade design guidelines.
- **Command:**
  ```bash
  npm install -g uipro-cli
  uipro init --ai gemini
  ```
