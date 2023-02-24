# 🧊 Tonic

An intuitive WordPress plugin development framework.

[![DeepSource](https://deepsource.io/gh/doodengineering/tonic.svg/?label=active+issues&show_trend=true&token=CR7CQYPYC3yIczV8tPRDDLnT)](https://deepsource.io/gh/doodengineering/tonic/?ref=repository-badge)

## Getting started

To get started, simply include it in your WordPress plugin's composer file.

```sh
composer install doodengineering/tonic
```

### Directory Structure

The filesystem works best when structured in the following way.

```sh
my-plugin/ # The root plugin folder.
├─ lib/ # <-- Contains composer dependencies.
├─ plugin/ # <-- Contains functionality.
│  ├─ Feature/ # <-- `Feature` classes.
│  ├─ View/ # <-- View-related files.
│  │  ├─ Component/ # <--  `Component` classes.
│  │  ├─ Shortcode/ # <--  `Shortcode` classes.
├─ resources/ # <-- Contains build dependencies.
│  ├─ css/ # <-- Stylesheets.
│  ├─ js/ # <-- Scripts.
│  ├─ views/ # <-- Blade templates.
├─ languages/ # <-- Contains translations.
│  ├─ my-plugin.pot # <-- Translations template.
├─ public/ # <-- Publicly available resources.
├─ my-plugin.php # <-- WordPress required.
```

## Documentation

The documentation is currently in progress. You can refer to the [wiki](https://github.com/doodengineering/tonic/wiki) for information.
