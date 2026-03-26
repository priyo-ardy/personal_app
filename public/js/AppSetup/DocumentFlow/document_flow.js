document.addEventListener("DOMContentLoaded", () => {
  getFlow();
});

const buttons = {
  save: document.getElementById("btnSave"),
  refresh: document.getElementById("refreshFlow"),
};

buttons.refresh.addEventListener("click", () => {
  getFlow();
});

buttons.save.addEventListener("click", () => {
  loading();
  fetchData(baseurl + "/document_flow/save", "POST", new FormData(formData))
    .then((result) => {
      pesanSukses(result.message);
      resetForm();
      document.getElementById("child_id").focus();
      getFlow();
      hideLoading();
    })
    .catch((err) => {
      pesanError(err.message);
      hideLoading();
    });
});

function resetForm() {
  formData.reset();
  const selectElement = document.querySelectorAll("select");

  selectElement.forEach((select) => {
    $(select).trigger("change");
  });
}

function getFlow() {
  loading();
  fetchData(baseurl + "/document_flow/get_flow", "GET")
    .then((result) => {
      hideLoading();

      const parsedNodes = JSON.parse(result.data.nodes);
      const parsedEdges = JSON.parse(result.data.edges);

      // 1. Format Data untuk AntV G6 (Butuh 'id', 'label', 'source', 'target')
      const nodes = parsedNodes.map((node) => ({
        id: node.id,
        label: node.label,
      }));

      const edges = parsedEdges.map((edge) => ({
        source: edge.from,
        target: edge.to,
      }));

      const container = document.getElementById("flow-network");
      container.innerHTML = ""; // Bersihkan wadah jika ada sisa grafik lama

      // 2. Konfigurasi Grafik G6
      const graph = new G6.Graph({
        container: "flow-network",
        width: container.scrollWidth || 1000,
        height: container.scrollHeight || 700,
        fitView: true, // Otomatis zoom-out agar semua kotak terlihat
        fitViewPadding: [20, 20, 20, 20],

        // Pengaturan Layout (Kita pakai Kiri ke Kanan lagi!)
        layout: {
          type: "dagre",
          rankdir: "LR", // Kiri ke Kanan
          nodesep: 40, // Jarak vertikal antar kotak (Atas-Bawah)
          ranksep: 100, // Jarak horizontal (Panjang garis Kiri-Kanan)
          controlPoints: true, // Mengizinkan garis berbelok siku-siku
        },

        // Desain Kotak
        defaultNode: {
          type: "rect",
          size: [240, 45], // Dibuat agak panjang (240px) agar teks stage-mu muat
          style: {
            fill: "#e7f1ff",
            stroke: "#007bff",
            lineWidth: 2,
            radius: 5,
          },
          labelCfg: {
            style: {
              fill: "#333",
              fontSize: 12,
            },
          },
        },

        // Desain Garis (INI KUNCI KERAPIANNYA)
        defaultEdge: {
          type: "polyline", // Menggunakan algoritma garis siku-siku
          style: {
            radius: 8, // Sudut belokan agak melengkung manis
            offset: 20, // Jarak garis keluar dari kotak sebelum berbelok
            endArrow: true,
            stroke: "#888",
            lineWidth: 1.5,
          },
        },

        // Fitur Interaksi UI
        modes: {
          default: ["drag-canvas", "zoom-canvas", "drag-node"],
        },
      });

      // 3. Masukkan Data dan Gambar!
      graph.data({ nodes, edges });
      graph.render();
    })
    .catch((err) => {
      hideLoading();
      console.error("Terjadi kesalahan:", err);
    });
}
