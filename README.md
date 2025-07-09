<p align="center">
<img src="https://github.com/VsrStudio/TeleportScreen/blob/main/icon.png" width="64"/><br>
</p>

# TeleportScreen

🎮 **TeleportScreen** is a PocketMine-MP API 5 plugin that displays a custom screen when a player teleports, respawns, or joins a server. This plugin also supports sound effects and a **freeze** feature to keep the game running smoothly.

<p align="center">
  <img src="https://github.com/VsrStudio/TeleportScreen/blob/main/63354992-59ab-4125-94e5-8a1ff684f4e6.gif" width="420"/>
</p>

## Feature

- 🎬 Show *screen effect* when teleporting, joining, or respawning.
- 🔇 Custom sounds can be played according to settings.
- ❄️ Freeze player movement during the delay.
- 🧱 Supports custom Resource Pack `.mcpack`.
- ⚙️ Settings can be changed in `config.yml`.

## Configuration
```yaml
# https://www.digminecraft.com/lists/sound_list_pe.php

screen-delay: 5 # Screen delay and freeze time (seconds)
screen-message: "§bTeleporting..." # Message when screen appears
screen-sound: "enderdragon.sigma" # Minecraft sound names
screen-freeze: true # Enable freeze player

screen-settings:
  join: true
  respawn: true
  teleport: true
```

## Custom Screen
Open File **TeleportScreen.mcpack**  Then open textures/ui/**teleport_screen.png** The size must be the same, namely **256x128**
