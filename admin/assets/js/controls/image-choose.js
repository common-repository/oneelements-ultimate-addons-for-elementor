jQuery( window ).on( 'elementor:init', function() {
    elementor.addControlView( 'image_choose', elementor.modules.controls.Choose );
});

function one_element_chunk(obj, chunkSize) {

    var values = Object.values(obj);

    var final = [],
        counter = 0,
        portion = {};

    for (var key of Object.keys(obj)) {
        if (counter !== 0 && counter % chunkSize === 0) {
            final.push(portion);
            portion = {};
        }
        portion[key] = values[counter];
        counter++
    }

    final.push(portion);

    return final;

}