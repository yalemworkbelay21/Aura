async function initDynamicContent() {
  try {
    const res = await fetch('api.php?action=get_all');
    const data = await res.json();
    
    // Site Settings Synchronization
    if (data.settings) {
      const s = data.settings;
      const map = {
        'dyn_phone': s.site_phone,
        'dyn_email': s.site_email,
        'dyn_address': s.site_address,
        'dyn_hours': s.site_hours,
        'foot_phone': s.site_phone,
        'foot_address': s.site_address,
        'foot_hours': 'Open: ' + s.site_hours,
        'dyn_hero_subtitle': s.hero_subtitle,
        'dyn_hero_title': s.hero_title ? s.hero_title.replace(/\n/g, '<br>') : null,
        'dyn_about_text': s.about_text
      };
      
      Object.keys(map).forEach(id => {
         const el = document.getElementById(id);
         if(el && map[id]) el.innerHTML = map[id];
      });

      // Email Links
      const eLinks = [document.getElementById('dyn_email_link'), document.getElementById('foot_email_link')];
      eLinks.forEach(l => { 
        if(l && s.site_email) { l.href = 'mailto:' + s.site_email; l.innerText = s.site_email; } 
      });

      // Phone Links
      const pLinks = [document.getElementById('dyn_phone_link'), document.getElementById('foot_phone_link')];
      pLinks.forEach(l => {
        if(l && s.site_phone) { l.href = 'tel:' + s.site_phone.split('/')[0].trim(); }
      });
    }

    // Dynamic Menu Rendering
    const menuList = document.getElementById('dyn_menu_list');
    if (menuList && data.menu && data.menu.length > 0) {
      menuList.innerHTML = data.menu.map(m => `
        <li>
          <div class="menu-card hover:card">
            <figure class="card-banner img-holder" style="--width: 100; --height: 100;">
              <img src="${m.image}" width="100" height="100" loading="lazy" alt="${m.title}" class="img-cover">
            </figure>
            <div>
              <div class="title-wrapper">
                <h3 class="title-3"><a href="#" class="card-title">${m.title}</a></h3>
                <span class="span title-2">$${m.price}</span>
              </div>
              <p class="card-text label-1">${m.description || ''}</p>
            </div>
          </div>
        </li>
      `).join('');
    }

    // Dynamic Gallery (Events) Rendering
    const galList = document.getElementById('dyn_gallery_list');
    if (galList && data.gallery && data.gallery.length > 0) {
      galList.innerHTML = data.gallery.map(g => `
        <li>
          <div class="event-card has-before hover:shine">
            <div class="card-banner img-holder" style="--width: 350; --height: 450;">
              <img src="${g.image}" width="350" height="450" loading="lazy" alt="${g.caption || ''}" class="img-cover">
              <time class="publish-date label-2">${new Date(g.created_at || Date.now()).toLocaleDateString()}</time>
            </div>
            <div class="card-content">
              <p class="card-subtitle label-2 text-center">Aura Selection</p>
              <h3 class="card-title title-2 text-center">${g.caption || 'Aura Experience'}</h3>
            </div>
          </div>
        </li>
      `).join('');
    }

  } catch (e) { 
    console.error("Aura Dynamic Sync Error:", e); 
  }
}

window.addEventListener('load', initDynamicContent);
