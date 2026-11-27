    <div class="modal fade" id="godModal" tabindex="-1" aria-labelledby="godModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-4" id="godModalTitle">Подробности</h2><button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body" id="godModalBody" aria-live="polite"></div>
                <div class="modal-footer">
                    <div class="modal-navigation me-auto"><button class="btn btn-outline-primary" id="previousGod"
                            type="button" aria-label="Предыдущий бог">←</button><button class="btn btn-outline-primary"
                            id="nextGod" type="button" aria-label="Следующий бог">→</button></div>
                    <a class="btn btn-outline-primary" id="godModalEdit" hidden>Редактировать</a>
                    <form id="godModalDeleteForm" hidden method="POST" class="m-0"
                          data-confirm-delete>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Удалить</button>
                    </form>
                    <a class="btn btn-primary" id="open-full-card" hidden>Открыть карточку</a>
                </div>
            </div>
        </div>
    </div>
