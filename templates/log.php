<div class="bg-body-tertiary p-5 rounded">
    <?php if (hasUserRole('admin')) { ?>
        <form id="rcon-form" class="d-flex gap-2 mt-3">
            <input class="form-control" id="rcon-command" placeholder="Minecraft Command (ex. say Hello)" autocomplete="off">
            <button class="btn btn-primary">Send</button>
        </form>
        <pre id="rcon-output" class="mt-2"></pre>
    <?php } ?>


    <h3>Server Log:</h3>
    <pre id="server-log"><?php echo $data ?></pre>
</div>