function initIndonesiaMap() {
  const root = am5.Root.new('indonesia-map');

  root.setThemes([am5themes_Animated.new(root)]);

  const chart = root.container.children.push(
    am5map.MapChart.new(root, {
      panX: 'none',
      panY: 'none',
      projection: am5map.geoMercator(),
      paddingBottom: 20,
      paddingTop: 20,
      paddingLeft: 20,
      paddingRight: 20,
    })
  );

  // Buat series polygon untuk peta
  const polygonSeries = chart.series.push(
    am5map.MapPolygonSeries.new(root, {
      geoJSON: am5geodata_indonesiaLow,
      exclude: ['AQ'],
      fill: am5.color(0x6699cc),
      stroke: am5.color(0xffffff),
    })
  );

  // Konfigurasi series
  polygonSeries.mapPolygons.template.setAll({
    tooltipText: '{name}',
    interactive: true,
    fill: am5.color(0x6699cc),
  });

  // Efek hover
  polygonSeries.mapPolygons.template.states.create('hover', {
    fill: am5.color(0xf59e0b),
  });

  // Event click
  polygonSeries.mapPolygons.template.events.on('click', function (ev) {
    const province = ev.target.dataItem?.dataContext;
    if (province) {
      console.log('Provinsi dipilih:', province.name);
    }
  });

  // Tambahkan zoom control
  chart.set('zoomControl', am5map.ZoomControl.new(root, {}));

  // Sembunyikan loading indicator
  document.querySelector('.loading-indicator').style.display = 'none';

  return { chart, polygonSeries };
}

// Fungsi untuk menampilkan daftar provinsi
function populateProvinceList(polygonSeries) {
  // Data provinsi
  const popularProvinces = [
    { id: 'ID-JK', name: 'DKI Jakarta', umkmCount: 850 },
    { id: 'ID-JB', name: 'Jawa Barat', umkmCount: 1250 },
    { id: 'ID-JT', name: 'Jawa Tengah', umkmCount: 980 },
    { id: 'ID-JI', name: 'Jawa Timur', umkmCount: 1100 },
    { id: 'ID-BT', name: 'Banten', umkmCount: 620 },
  ];

  // Populasi daftar provinsi
  const provinceList = document.getElementById('province-list');

  popularProvinces.forEach((province) => {
    const item = document.createElement('div');
    item.className = 'province-item flex items-center p-3 bg-white rounded-lg cursor-pointer';
    item.innerHTML = `
          <div class="w-8 h-8 rounded-full bg-primary bg-opacity-10 text-primary flex items-center justify-center mr-3">
              <i class="fas fa-map-marker-alt text-sm"></i>
          </div>
          <div>
              <h4 class="font-medium">${province.name}</h4>
              <p class="text-xs text-gray-500">${province.umkmCount} UMKM</p>
          </div>
      `;

    // Event click untuk highlight peta
    item.addEventListener('click', () => {
      polygonSeries.mapPolygons.each((polygon) => {
        if (polygon.dataItem?.dataContext?.id === province.id) {
          polygonSeries.triggerHoverOnDataItem(polygon.dataItem);
          polygon.events.dispatch('click');
        }
      });
    });

    // Efek hover
    item.addEventListener('mouseenter', () => {
      item.classList.add('shadow-md');
      item.style.transform = 'translateY(-2px)';
    });

    item.addEventListener('mouseleave', () => {
      item.classList.remove('shadow-md');
      item.style.transform = '';
    });

    provinceList.appendChild(item);
  });
}

// Fungsi untuk toggle mobile menu
function initMobileMenu() {
  const button = document.getElementById('mobile-menu-button');
  const menu = document.querySelector('.md-hidden'); // Sesuaikan dengan class di HTML

  if (button && menu) {
    button.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });
  }
}

// Inisialisasi semua ketika DOM siap
document.addEventListener('DOMContentLoaded', () => {
  // Inisialisasi peta
  const { polygonSeries } = initIndonesiaMap();
  populateProvinceList(polygonSeries);

  // Inisialisasi mobile menu
  initMobileMenu();

  // Smooth scrolling
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        window.scrollTo({
          top: target.offsetTop - 80,
          behavior: 'smooth',
        });
      }
    });
  });
});
