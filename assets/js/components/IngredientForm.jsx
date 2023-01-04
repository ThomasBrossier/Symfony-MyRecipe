import React, {useEffect, useState} from 'react';
import {ErrorMessage, Field, FieldArray, Form, Formik, getIn} from 'formik';
import {Autocomplete, InputLabel, ListSubheader, MenuItem, Select, TextField} from "@mui/material";

const IngredientForm = ({index, remove,...props}) => {
    const [currentIngredients, setCurrentIngredients] = useState([{name: ''}])
    const handleIngredientChange = (e)=>{
        setTimeout(()=>{
            fetch('https://127.0.0.1:8000/api/recipe/ingredients?' + new URLSearchParams({
                ingredient : e.target.value,
            }))
                .then(res=> res.json())
                .then(data => {
                    setCurrentIngredients(data);
                });
            console.log(e.target.value)
        },1000)
    }
    return (
        <div className="d-flex flex-row align-items-center my-1">
            <div className="d-flex flex-column mx-2">
                <Field
                    {...props}
                    component={Autocomplete}
                    options={currentIngredients}
                    getOptionLabel={(option ) => option.name}
                    isOptionEqualToValue={(option, value) => option.id === value.id}
                    sx={{ width: 400 }}
                    name={`ingredients.${index}.name`}
                    /*error={props.errors.ingredients[index].name ? true : false}*/
                    error={getIn(props.errors, `ingredients.${index}.name`) &&
                        getIn(props.touched, `ingredients.${index}.name`)}
                    renderInput={(params=>
                    <TextField {...params}
                           helperText={<ErrorMessage name={`ingredients.${index}.name`} className="text-danger"/> }
                           label="Ingredient... (ex : tomate)"
                           size="small"
                           onKeyUp={(e)=> handleIngredientChange(e)}
                    />
                )}/>
            </div>
            <div className="d-flex flex-column mx-2 w-50">
                <Field type="number"
                       as={TextField}
                       helperText={<ErrorMessage name={`ingredients.${index}.quantity`}/>}
                       error={getIn(props.errors, `ingredients.${index}.quantity`) &&
                           getIn(props.touched, `ingredients.${index}.quantity`)}
                       size="small"
                       label="Quantité... (ex : 10)"
                       name={`ingredients.${index}.quantity`}
                />
            </div>
            <div className="d-flex flex-column mx-2 w-50">
                <Field
                    as={TextField}
                    select
                    size="small"
                    helperText={<ErrorMessage name={`ingredients.${index}.unit`}/> }
                    error={getIn(props.errors, `ingredients.${index}.unit`) &&
                        getIn(props.touched, `ingredients.${index}.unit`)}
                    label="Veuillez sélectionner une unité"
                    id={`ingredients.${index}.unit`}
                    name={`ingredients.${index}.unit`}
                    defaultValue=""
                    fullWidth
                >
                    <ListSubheader className="text-black text-bg-light">Solide</ListSubheader>
                        <MenuItem className="text-secondary" value="mg">Milligramme (mg)</MenuItem>
                        <MenuItem className="text-secondary" value="g">Gramme (g)</MenuItem>
                        <MenuItem className="text-secondary" value="Kg">Kilogramme (Kg)</MenuItem>
                    <ListSubheader className="text-black text-bg-light">Liquide</ListSubheader>
                        <MenuItem className="text-secondary" value="ml">Millilitre (ml)</MenuItem>
                        <MenuItem className="text-secondary" value="cl">Centilitre (cl)</MenuItem>
                        <MenuItem className="text-secondary" value="L">Litre (L)</MenuItem>
                    <ListSubheader className="text-black text-bg-light">Autre</ListSubheader>
                        <MenuItem className="text-secondary" value="bouquet">bouquet</MenuItem>
                        <MenuItem className="text-secondary" value="gousse">gousse</MenuItem>
                        <MenuItem className="text-secondary" value="graine">graine</MenuItem>
                        <MenuItem className="text-secondary" value="pince">pincée</MenuItem>
                </Field>
            </div>
            <button
                type="button"
                className="btn btn-danger"
                onClick={() => remove(index)}
            >
                X
            </button>
        </div>
    )

}
export default IngredientForm;
