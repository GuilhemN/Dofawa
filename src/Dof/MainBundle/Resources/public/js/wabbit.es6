$(function () {
    runAsync(function* () {
        var wabbit = getImage('/graphics/render/7b3234337d/full/1/256_256-0.png');
        yield sleep(Math.round(Math.random() * 5000));
        if (yield dialogConfirm('Coucou tu veux voir Wabbit ?', 'Wabbit', 'Wiiiiii', 'Dégage !'))
            yield dialogAlert(yield wabbit, 'Wabbit', 'Dégage !');
    });
});
