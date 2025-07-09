# TeleportScreen

🎮 **TeleportScreen** is a PocketMine-MP API 5 plugin that displays a custom screen (UI & animation) when a player teleports, respawns, or joins a server. This plugin also supports sound effects and a **freeze** feature to keep the game running smoothly.

![TeleportScreen Demo](https://github.com/VsrStudio/TeleportScreen/blob/main/63354992-59ab-4125-94e5-8a1ff684f4e6.gif)

## Feature

- 🎬 Show *screen effect* when teleporting, joining, or respawning.
- 🔇 Custom sounds can be played according to settings.
- ❄️ Freeze player movement during the delay.
- 🧱 Supports custom Resource Pack `.mcpack`.
- ⚙️ Settings can be changed in `config.yml`.

## Configuration
```yaml
screen-delay: 5 # Waktu delay screen dan freeze (detik)
screen-message: "§bTeleporting..." # Pesan saat screen tampil
screen-sound: "ambient.frozen" # Nama sound Minecraft
screen-freeze: true # Aktifkan freeze player

screen-settings:
  join: true
  respawn: true
  teleport: true
```

## Custom Screen
Open File **TeleportScreen.mcpack**  Then open textures/ui/**teleport_screen.png** The size must be the same, namely **256x128**
