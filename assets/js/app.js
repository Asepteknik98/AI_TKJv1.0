document.addEventListener('DOMContentLoaded', function(){
  // Attach AJAX delete handlers for materi
  document.querySelectorAll('.btn-delete').forEach(function(btn){
    btn.addEventListener('click', function(){
      var id = this.dataset.id;
      if (!confirm('Hapus materi ini?')) return;
      fetch('/AI_TKJ/guru/materi_delete.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'id=' + encodeURIComponent(id)
      }).then(r=>r.json()).then(function(res){
        if (res.success) {
          // remove card
          var card = document.querySelector('.btn-delete[data-id="'+id+'"]').closest('.col-md-4');
          if (card) card.remove();
        } else {
          alert('Gagal menghapus: ' + (res.error||'unknown'));
        }
      }).catch(function(){ alert('Network error'); });
    });
  });
});
