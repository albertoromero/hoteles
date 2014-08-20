function resetCalories()
{
    document.caloriesForm.reset();
    document.getElementById("caloriesResult").innerHTML = "";
}

function getCalories()
{
    weight = parseInt(document.caloriesForm.weight.value);
    minutes = parseInt(document.caloriesForm.minutes.value);
    exercise = document.caloriesForm.exercise[document.caloriesForm.exercise.selectedIndex].value;

    switch(exercise) {
        case "run1":
            level = .082;
            break;
        case "run2":
            level = .102;
            break;
        case "run3":
            level = .142;
            break;
        case "bike":
            level = .048;
            break;
        case "swim":
            level = .046;
            break;
        case "walk":
            level = .100;
            break;
        default:
            level = .050;
    }

/*    if (exercise=="aerobic")
    {
       level = .046;
    }
    if (exercise=="ciclismo")
    {
       level = .048;
    }
    if (exercise=="bailar")
    {
       level = .034;
    }
    if (exercise=="correr1")
    {
       level = .082;
    }
    if (exercise=="correr7")
    {
       level = .102;
    }
    if (exercise=="correr10")
    {
       level = .142;
    }
    if (exercise=="tenis")
    {
       level = .049;
    }
    if (exercise=="caminar2")
    {
       level = .026;
    }
    if (exercise=="caminar3")
    {
       level = .035;
    }
    if (exercise=="caminar4")
    {
       level = .048;
       }
    if (exercise=="futbol")
    {
       level = .061;
    }
    if (exercise=="golf")
    {
       level = .029;
    }
    if (exercise=="basket")
    {
       level = .045;
    }
    if (exercise=="limpiar")
    {
       level = .048;
    }
    if (exercise=="besar")
    {
       level = .008;
    }
    if (exercise=="pintar")
    {
       level = .048;
    }
    if (exercise=="limpiarcoche")
    {
       level = .034;
    }
    if (exercise=="vertv")
    {
       level = .008;
    }
    if (exercise=="tenis2")
    {
       level = .036;
    }
    if (exercise=="kayak")
    {
       level = .045;
    }
    if (exercise=="spinning")
    {
       level = .053;
    }*/

    var level=level;

    aux_calories = (weight*2.2)*minutes*level;
    aux_calories = Math.round(aux_calories*10)/10;
    document.getElementById("caloriesResult").innerHTML = aux_calories;
}

function pushCalories()
{
  getCalories();
  $('#calories').val(aux_calories);
  $('#caloriesModal').removeClass('in');
}

