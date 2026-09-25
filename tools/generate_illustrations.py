import math, os
OUT = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "audira", "assets", "images")
os.makedirs(OUT, exist_ok=True)

DEFS = '''<defs>
<radialGradient id="bg" cx="50%" cy="42%" r="70%"><stop offset="0" stop-color="{bg1}"/><stop offset="1" stop-color="{bg2}"/></radialGradient>
<linearGradient id="champ" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#FFFDF9"/><stop offset=".45" stop-color="#EFE5D6"/><stop offset="1" stop-color="#C9B79C"/></linearGradient>
<linearGradient id="champ2" x1="1" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#FFFDF9"/><stop offset=".5" stop-color="#EDE2D1"/><stop offset="1" stop-color="#BFAA8C"/></linearGradient>
<linearGradient id="graph" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#6B767C"/><stop offset=".5" stop-color="#3A4449"/><stop offset="1" stop-color="#1B2226"/></linearGradient>
<linearGradient id="silver" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#FFFFFF"/><stop offset=".5" stop-color="#E4E8EA"/><stop offset="1" stop-color="#A9B3B8"/></linearGradient>
<linearGradient id="tealcase" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2C7D7D"/><stop offset="1" stop-color="#0A4142"/></linearGradient>
<radialGradient id="dome" cx="40%" cy="35%" r="70%"><stop offset="0" stop-color="#FFFFFF" stop-opacity=".95"/><stop offset=".7" stop-color="#DCE8E8" stop-opacity=".85"/><stop offset="1" stop-color="#9FB9BA" stop-opacity=".9"/></radialGradient>
<linearGradient id="clear" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#F7E6D6" stop-opacity=".95"/><stop offset="1" stop-color="#D9AE8C" stop-opacity=".9"/></linearGradient>
<linearGradient id="hl" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fff" stop-opacity=".9"/><stop offset="1" stop-color="#fff" stop-opacity="0"/></linearGradient>
<radialGradient id="shadow" cx="50%" cy="50%" r="50%"><stop offset="0" stop-color="#0E1E25" stop-opacity=".28"/><stop offset="1" stop-color="#0E1E25" stop-opacity="0"/></radialGradient>
<filter id="soft" x="-60%" y="-60%" width="220%" height="240%"><feDropShadow dx="0" dy="14" stdDeviation="16" flood-color="#0E1E25" flood-opacity=".18"/></filter>
</defs>'''

THEMES = {
    "sage": ("#EEF4F0", "#D6E6DF"),
    "sand": ("#FBF6EE", "#EBDFCC"),
    "mist": ("#F1F5F6", "#DAE5E8"),
    "blush": ("#FBF3EE", "#EFDCD0"),
    "night": ("#1B3238", "#0E1E25"),
}

def svg(w, h, theme, body, rings=True, rc=None):
    b1, b2 = THEMES[theme]
    cx, cy = rc or (w * .5, h * .5)
    ring = ""
    if rings:
        col = "#FFFFFF" if theme != "night" else "#FBF8F3"
        op = .55 if theme != "night" else .06
        for i, r in enumerate((h * .28, h * .4, h * .52)):
            ring += f'<circle cx="{cx}" cy="{cy}" r="{r:.0f}" fill="none" stroke="{col}" stroke-opacity="{op - i*0.12 if theme!="night" else op}" stroke-width="2"/>'
    return (f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w} {h}" width="{w}" height="{h}">'
            + DEFS.replace("{bg1}", b1).replace("{bg2}", b2)
            + f'<rect width="{w}" height="{h}" fill="url(#bg)"/>' + ring + body + '</svg>')

def shadow(cx, cy, rx, ry=None):
    ry = ry or rx * .16
    return f'<ellipse cx="{cx}" cy="{cy}" rx="{rx}" ry="{ry}" fill="url(#shadow)"/>'

# ---------- device parts ----------
def ric_body(tx, ty, s=1, rot=0, mat="champ", led=True):
    # small crescent case, origin roughly at top of case
    return f'''<g transform="translate({tx} {ty}) rotate({rot}) scale({s})" filter="url(#soft)">
<path d="M0 0C38 -6 70 22 72 70C74 120 52 168 18 180C2 186 -10 176 -6 160C8 110 12 60 -4 22C-9 10 -8 1 0 0Z" fill="url(#{mat})"/>
<path d="M4 8C30 6 52 26 56 62" fill="none" stroke="url(#hl)" stroke-width="7" stroke-linecap="round" opacity=".8"/>
<path d="M-2 22C14 62 12 112 -2 158" fill="none" stroke="#000" stroke-opacity=".08" stroke-width="3"/>
<rect x="44" y="84" width="10" height="34" rx="5" fill="#000" fill-opacity=".14"/>
<circle cx="30" cy="30" r="3" fill="#000" fill-opacity=".25"/><circle cx="40" cy="40" r="3" fill="#000" fill-opacity=".25"/>
{'<circle cx="22" cy="150" r="4" fill="#3FC08A"/>' if led else ''}
</g>'''

def wire_and_dome(x0, y0, x1, y1, c1, c2, dome_rot=0, wire="#3A4449", s=1):
    return f'''<path d="M{x0} {y0}C{c1[0]} {c1[1]} {c2[0]} {c2[1]} {x1} {y1}" fill="none" stroke="{wire}" stroke-width="{6*s}" stroke-linecap="round"/>
<path d="M{x0} {y0}C{c1[0]} {c1[1]} {c2[0]} {c2[1]} {x1} {y1}" fill="none" stroke="#fff" stroke-opacity=".35" stroke-width="{2*s}" stroke-linecap="round" transform="translate(-1.5 -1.5)"/>
<g transform="translate({x1} {y1}) rotate({dome_rot}) scale({s})" filter="url(#soft)">
<rect x="-11" y="-4" width="22" height="34" rx="9" fill="url(#graph)"/>
<path d="M-26 44C-26 22 -14 18 0 18C14 18 26 22 26 44C26 58 14 64 0 64C-14 64 -26 58 -26 44Z" fill="url(#dome)" stroke="#9FB9BA" stroke-width="1.5"/>
<path d="M-16 34C-12 26 -4 24 4 25" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" opacity=".9"/>
<path d="M-8 60V46M0 62V46M8 60V46" stroke="#9FB9BA" stroke-width="2" stroke-linecap="round"/>
</g>'''

def ric_device(tx, ty, s=1, mat="champ", rot=0, flip=False):
    sx = -s if flip else s
    return f'''<g transform="translate({tx} {ty}) rotate({rot}) scale({sx} {s})">
{wire_and_dome(2, 4, -70, 170, (-20, -40), (-90, 40), dome_rot=12)}
{ric_body(0, 0, 1, 0, mat)}
</g>'''

def bte_device(tx, ty, s=1, mat="champ2"):
    return f'''<g transform="translate({tx} {ty}) scale({s})">
<path d="M8 0C-40 -46 -130 -30 -140 60C-146 120 -126 150 -134 196" fill="none" stroke="#DCEBEA" stroke-opacity=".9" stroke-width="16" stroke-linecap="round"/>
<path d="M8 0C-40 -46 -130 -30 -140 60C-146 120 -126 150 -134 196" fill="none" stroke="#fff" stroke-opacity=".8" stroke-width="4" stroke-linecap="round" transform="translate(-4 -3)"/>
<path d="M8 0C-40 -46 -130 -30 -140 60C-146 120 -126 150 -134 196" fill="none" stroke="#8FB3B3" stroke-opacity=".5" stroke-width="16" stroke-linecap="round" stroke-dasharray="0 0" transform="translate(3 3)" opacity=".25"/>
<g filter="url(#soft)">
<path d="M-178 210C-150 170 -96 176 -86 228C-78 272 -110 306 -150 300C-188 294 -204 246 -178 210Z" fill="url(#clear)"/>
<path d="M-168 222C-150 200 -122 200 -110 222" fill="none" stroke="#fff" stroke-width="6" stroke-linecap="round" opacity=".8"/>
<circle cx="-136" cy="262" r="7" fill="#8A5A3C" opacity=".45"/>
</g>
<g filter="url(#soft)">
<path d="M-6 -10C70 -24 130 40 128 140C126 240 80 318 20 330C-8 336 -22 318 -14 294C8 220 14 120 -18 46C-28 20 -24 -6 -6 -10Z" fill="url(#{mat})"/>
<path d="M4 2C56 0 100 44 108 118" fill="none" stroke="url(#hl)" stroke-width="10" stroke-linecap="round"/>
<path d="M-10 48C18 118 14 214 -8 290" fill="none" stroke="#000" stroke-opacity=".08" stroke-width="4"/>
<rect x="84" y="120" width="16" height="60" rx="8" fill="#000" fill-opacity=".14"/>
<circle cx="112" cy="232" r="14" fill="#000" fill-opacity=".1"/><circle cx="112" cy="232" r="8" fill="#000" fill-opacity=".12"/>
<circle cx="54" cy="46" r="4" fill="#000" fill-opacity=".25"/><circle cx="70" cy="62" r="4" fill="#000" fill-opacity=".25"/>
</g>
<rect x="-6" y="-16" width="26" height="22" rx="8" fill="url(#silver)" transform="rotate(-18)"/>
</g>'''

def ite_device(tx, ty, s=1, mat="champ", face="graph", mark="#E0584F"):
    return f'''<g transform="translate({tx} {ty}) scale({s})" filter="url(#soft)">
<path d="M-120 -40C-110 -120 -10 -150 70 -120C150 -90 170 0 130 70C100 124 60 112 40 150C22 186 -40 196 -80 160C-130 116 -128 30 -120 -40Z" fill="url(#{mat})"/>
<path d="M-96 -70C-70 -118 0 -130 56 -108" fill="none" stroke="url(#hl)" stroke-width="12" stroke-linecap="round"/>
<ellipse cx="4" cy="10" rx="92" ry="80" fill="url(#{face})" transform="rotate(-12 4 10)"/>
<ellipse cx="-4" cy="-2" rx="78" ry="66" fill="none" stroke="#fff" stroke-opacity=".12" stroke-width="3" transform="rotate(-12 4 10)"/>
<rect x="-40" y="10" width="64" height="44" rx="14" fill="#000" fill-opacity=".3" transform="rotate(-12 4 10)"/>
<path d="M-30 20h44" stroke="#fff" stroke-opacity=".18" stroke-width="3" stroke-linecap="round" transform="rotate(-12 4 10)"/>
<circle cx="-34" cy="-34" r="7" fill="#000" fill-opacity=".45"/><circle cx="-6" cy="-44" r="7" fill="#000" fill-opacity=".45"/>
<circle cx="52" cy="-10" r="10" fill="#000" fill-opacity=".35"/>
<circle cx="40" cy="44" r="6" fill="{mark}"/>
</g>'''

def iic_device(tx, ty, s=1, rot=0, mat="graph", mark="#E0584F"):
    return f'''<g transform="translate({tx} {ty}) rotate({rot}) scale({s})">
<path d="M8 -40C30 -80 60 -104 84 -112" fill="none" stroke="#9FB9BA" stroke-width="4" stroke-linecap="round"/>
<circle cx="86" cy="-113" r="7" fill="#C9D8D8"/>
<g filter="url(#soft)">
<path d="M-36 -36C-10 -58 34 -46 44 -16C56 20 40 80 12 118C-6 142 -40 140 -50 110C-62 70 -64 -12 -36 -36Z" fill="url(#{mat})"/>
<path d="M-30 -30C-12 -44 16 -40 28 -24" fill="none" stroke="#fff" stroke-opacity=".5" stroke-width="7" stroke-linecap="round"/>
<ellipse cx="-2" cy="-26" rx="40" ry="14" fill="#000" fill-opacity=".22" transform="rotate(12 -2 -26)"/>
<circle cx="-12" cy="-26" r="4.5" fill="#000" fill-opacity=".5"/>
<circle cx="18" cy="-20" r="5" fill="{mark}"/>
<path d="M-20 116C-8 126 6 124 12 118" fill="none" stroke="#fff" stroke-opacity=".35" stroke-width="4" stroke-linecap="round"/>
</g></g>'''

def charging_case(cx, cy, s=1, open_=True):
    return f'''<g transform="translate({cx} {cy}) scale({s})">
<g filter="url(#soft)">
<path d="M-190 -40H190C200 -40 206 -32 204 -22L186 100C182 128 160 146 130 146H-130C-160 146 -182 128 -186 100L-204 -22C-206 -32 -200 -40 -190 -40Z" fill="url(#silver)"/>
<path d="M-176 -26H176" stroke="#fff" stroke-width="6" stroke-linecap="round" opacity=".9"/>
<path d="M-160 -34C-150 20 150 20 160 -34Z" fill="#1F2A2E" opacity=".9"/>
<circle cx="-20" cy="112" r="5" fill="#3FC08A"/><circle cx="0" cy="112" r="5" fill="#3FC08A"/><circle cx="20" cy="112" r="5" fill="#3FC08A" opacity=".35"/>
</g>
<g filter="url(#soft)">
<path d="M-196 -52C-196 -150 -150 -200 -120 -210H120C150 -200 196 -150 196 -52Z" fill="url(#silver)"/>
<path d="M-176 -60C-176 -140 -140 -182 -112 -190H112C140 -182 176 -140 176 -60Z" fill="#1F2A2E"/>
<path d="M-150 -170H150" stroke="#fff" stroke-opacity=".08" stroke-width="30" stroke-linecap="round"/>
</g></g>'''

def earbud(tx, ty, s=1, mat="graph", mark="#E0584F", flip=False, rot=0):
    sx = -s if flip else s
    return f'''<g transform="translate({tx} {ty}) rotate({rot}) scale({sx} {s})" filter="url(#soft)">
<ellipse cx="34" cy="10" rx="34" ry="30" fill="url(#dome)" stroke="#9FB9BA" stroke-width="1.5"/>
<path d="M-70 -60C-20 -110 60 -90 70 -30C78 20 40 70 -10 76C-60 82 -110 50 -110 0C-110 -26 -94 -44 -70 -60Z" fill="url(#{mat})"/>
<path d="M-84 -40C-60 -80 -10 -90 30 -76" fill="none" stroke="#fff" stroke-opacity=".45" stroke-width="10" stroke-linecap="round"/>
<circle cx="-36" cy="0" r="40" fill="#000" fill-opacity=".18"/>
<circle cx="-36" cy="0" r="30" fill="none" stroke="#fff" stroke-opacity=".15" stroke-width="3"/>
<circle cx="-36" cy="0" r="6" fill="{mark}"/>
</g>'''

def pebble(cx, cy, s=1):
    return f'''<g transform="translate({cx} {cy}) scale({s})" filter="url(#soft)">
<rect x="-150" y="-70" width="300" height="140" rx="70" fill="url(#graph)"/>
<path d="M-150 -6H150" stroke="#000" stroke-opacity=".35" stroke-width="3"/>
<path d="M-110 -52H110" stroke="#fff" stroke-opacity=".25" stroke-width="10" stroke-linecap="round"/>
<circle cx="0" cy="30" r="5" fill="#F0A63A"/>
</g>'''

def dryer(cx, cy, s=1):
    return f'''<g transform="translate({cx} {cy}) scale({s})">
<g filter="url(#soft)">
<path d="M-150 -30H150V110C150 150 110 170 0 170C-110 170 -150 150 -150 110Z" fill="url(#silver)"/>
<ellipse cx="0" cy="-30" rx="150" ry="46" fill="#EEF2F3"/>
<ellipse cx="0" cy="-30" rx="120" ry="34" fill="#2A3A40"/>
<ellipse cx="0" cy="-30" rx="120" ry="34" fill="#6FD3E0" opacity=".25"/>
<ellipse cx="0" cy="-26" rx="60" ry="14" fill="#9BE7F0" opacity=".45"/>
<circle cx="0" cy="110" r="10" fill="#2A3A40"/><circle cx="0" cy="110" r="4" fill="#6FD3E0"/>
</g>
<g filter="url(#soft)" transform="translate(0 -150)">
<ellipse cx="0" cy="44" rx="150" ry="44" fill="url(#silver)"/>
<path d="M-150 44V14C-150 -20 -80 -34 0 -34C80 -34 150 -20 150 14V44" fill="url(#silver)"/>
<ellipse cx="0" cy="12" rx="118" ry="30" fill="#fff" opacity=".55"/>
</g></g>'''

def brush(tx, ty, rot=-28, s=1):
    return f'''<g transform="translate({tx} {ty}) rotate({rot}) scale({s})" filter="url(#soft)">
<rect x="-12" y="-150" width="24" height="230" rx="12" fill="#0F5B5C"/>
<rect x="-6" y="-140" width="6" height="200" rx="3" fill="#fff" opacity=".25"/>
<path d="M-4 80V120" stroke="#6B767C" stroke-width="5"/>
<g stroke="#2A3A40" stroke-width="3" stroke-linecap="round">{''.join(f'<path d="M{-14+i*4} 100L{-20+i*6} 132"/>' for i in range(8))}</g>
<circle cx="0" cy="-150" r="6" fill="#6B767C"/>
</g>'''

def amplifier(tx, ty, s=1):
    return f'''<g transform="translate({tx} {ty}) scale({s})">
{bte_device(0, 0, 1, mat="graph")}
</g>'''

# -------- product type images (800x600) --------
W, H = 800, 600
files = {}
files["type-bte.svg"] = svg(W, H, "sand", shadow(430, 520, 200) + bte_device(470, 170, .95), rc=(430, 300))
files["type-ric.svg"] = svg(W, H, "sage", shadow(410, 505, 180) + ric_device(470, 150, 1.55, "champ", rot=8), rc=(410, 300))
files["type-ite.svg"] = svg(W, H, "mist", shadow(400, 500, 190) + ite_device(400, 290, 1.08, "champ"), rc=(400, 300))
files["type-iic.svg"] = svg(W, H, "blush", shadow(400, 500, 210) + iic_device(320, 300, 1.35, -14, "graph", "#3E7BD9") + iic_device(500, 290, 1.35, 12, "champ", "#E0584F"), rc=(400, 300))

# -------- picks (800x600) --------
files["pick-rechargeable.svg"] = svg(W, H, "sage",
    shadow(400, 520, 260) + charging_case(400, 380, .95)
    + ric_device(300, 150, 1.05, "champ", rot=-6) + ric_device(500, 150, 1.05, "champ", rot=6, flip=True), rc=(400, 300))
files["pick-earbud.svg"] = svg(W, H, "mist",
    shadow(400, 525, 270) + pebble(300, 430, .95) + earbud(520, 250, 1.05, "graph", "#3E7BD9", rot=18) + earbud(590, 420, 1.05, "graph", "#E0584F", flip=True, rot=-24), rc=(420, 300))
files["pick-invisible.svg"] = svg(W, H, "blush",
    shadow(400, 505, 220) + iic_device(330, 290, 1.25, -18, "champ", "#3E7BD9") + iic_device(500, 285, 1.25, 16, "champ", "#E0584F"), rc=(410, 300))
files["pick-amplifier.svg"] = svg(W, H, "sand",
    shadow(420, 520, 200) + amplifier(470, 175, .92), rc=(420, 300))
files["pick-care.svg"] = svg(W, H, "mist",
    shadow(380, 520, 240) + dryer(360, 330, .95) + brush(620, 330, -24, 1.1), rc=(400, 300))

# -------- hero (1000x1100) --------
hero_body = (
    '<circle cx="520" cy="560" r="330" fill="#FBF8F3" opacity=".75"/>'
    + shadow(520, 960, 300, 40)
    + ric_device(640, 190, 2.9, "champ", rot=10)
)
files["hero-device.svg"] = svg(1000, 1100, "sage", hero_body, rc=(520, 560))

for name, content in files.items():
    with open(os.path.join(OUT, name), "w") as fh:
        fh.write(content)
print("wrote", len(files))
