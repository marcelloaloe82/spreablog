<div id="modera" class="tab-pane fade <?php echo ($ruolo_utente == 'editor' ?  'active in' : ''); ?>">
      
      <table id="commenti" class="cell-border compact stripe" style="width: 100%;">
          <thead>
            
            <tr>
              <th>Nome</th>
              <th>Email</th>
              <th>Indirizzo IP</th>
              <th>News</th>
              <th>Vedi commento</th>
              <th>Contenuto</th>
              <th>Cancella</th>
              <th>Rispondi</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
    </div>
    <div id="editor" class="tab-pane fade">

        <h2>Scrivi articolo</h2>

        <form id="news-form">
        
          <div class="form-group">
            <input type="text" name="title" id="title" class="form-control" placeholder="Titolo" value="<?php if(!empty($title)) echo $title; ?>">
          </div>
        
        
          <textarea id="news-text" name="content" class="form-control" rows="5">
            <?php if(!empty($content)) echo $content; ?>
          </textarea>
          <div class="form-group">
            <label for="interested_users">Persone interessate: </label>
            <select class="form-control" name="interested_authors" id="interested_authors" multiple="">
              <?php foreach ($users as $user) : ?>
                <?php if ($user['id'] != $this->session->user['id']): ?>
                <option value="<?php echo $user['id']; ?>"><?php echo $user['nome'] . ' ' . $user['cognome']; ?></option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select> 
          </div>
          <div class="news-buttons">
            <button id="publish" class="btn btn-primary">Pubblica</button>
          </div>

          <input type="hidden" name="id" id="post-id" value="<?php if(!empty($id)) echo $id; ?>">
          <input type="hidden" id="<?php echo $csrf['name']; ?>" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>">
        </form>
        
    </div>