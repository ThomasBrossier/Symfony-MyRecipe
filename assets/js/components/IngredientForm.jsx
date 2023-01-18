import React, { useState} from 'react';
import {ErrorMessage, FastField, Field, getIn, isEmptyArray} from 'formik';
import {Autocomplete, CircularProgress, ListSubheader, MenuItem, Select, TextField} from "@mui/material";

const IngredientForm = ({index, remove,setFieldValue,...props}) => {
    const [currentIngredients, setCurrentIngredients] = useState([{name: ''}])
    const [loading , setLoading ] = useState(false);
    const handleIngredientChange = (e)=>{
        setLoading(true);
        setTimeout(()=>{
            fetch('https://127.0.0.1:8000/api/recipe/ingredients?' + new URLSearchParams({
                ingredient : e.target.value,
            }))
                .then(res=> res.json())
                .then(data => {
                    setLoading(false);
                    setCurrentIngredients(data);
                });
        },500)

    }
    return (
        <div className="d-flex flex-row align-items-start my-1">
            <div className="d-flex flex-column mx-2">
                <Field
                    {...props}

                    component={Autocomplete}
                    loading={loading}
                    loadingText={"Chargement..."}
                    noOptionsText={"Cette ingredient n'est pas répertorié"}
                    onChange={(e, value) => {
                        setFieldValue(`ingredients.${index}.name`, value ? value.id : "");
                    } }
                    options={currentIngredients}
                    getOptionLabel={(option ) => option.name}
                    isOptionEqualToValue={(option, value) => option.id === value.id}
                    sx={{ width: 400 }}
                    name={`ingredients.${index}.name`}
                    renderInput={(params=>
                    <TextField {...params}
                           helperText={<ErrorMessage name={`ingredients.${index}.name`} className="text-danger"/> }
                           label="Ingredient... (ex : tomate)"
                           size="small"
                           error={getIn(props.errors, `ingredients.${index}.name`) &&
                                   getIn(props.touched, `ingredients.${index}.name`)}
                           onChange={(e)=> handleIngredientChange(e)}
                           InputProps={{
                                ...params.InputProps,
                                endAdornment: (
                                    <React.Fragment>
                                        {loading ? <CircularProgress color="inherit" size={20} /> : null}
                                        {params.InputProps.endAdornment}
                                    </React.Fragment>
                                   ),
                               }}
                    />
                )}/>
            </div>
            <div className="d-flex flex-column mx-2 w-50">
                <FastField type="number"
                       as={TextField}
                       InputProps={{
                           inputProps: {
                               min: 0,
                               max:10000,
                               step:'0.5'
                           }
                       }}
                       helperText={<ErrorMessage className="text-danger" name={`ingredients.${index}.quantity`}/>}
                       error={getIn(props.errors, `ingredients.${index}.quantity`) &&
                           getIn(props.touched, `ingredients.${index}.quantity`)}
                       size="small"
                       label="Quantité... (ex : 10)"
                       name={`ingredients.${index}.quantity`}
                />
            </div>
            <div className="d-flex flex-column mx-2 w-50">
                <FastField
                    as={TextField}
                    select
                    size="small"
                    helperText={<ErrorMessage className="text-danger" name={`ingredients.${index}.unit`}/> }
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
                        <MenuItem className="text-secondary" value="cm">Centimetre (cm)</MenuItem>
                        <MenuItem className="text-secondary" value="cs">C. à soupe</MenuItem>
                        <MenuItem className="text-secondary" value="cc">C. à café</MenuItem>
                    <ListSubheader className="text-black text-bg-light">Liquide</ListSubheader>
                        <MenuItem className="text-secondary" value="ml">Millilitre (ml)</MenuItem>
                        <MenuItem className="text-secondary" value="cl">Centilitre (cl)</MenuItem>
                        <MenuItem className="text-secondary" value="L">Litre (L)</MenuItem>
                    <ListSubheader className="text-black text-bg-light">Autre</ListSubheader>
                        <MenuItem className="text-secondary" value="bouquet">bouquet</MenuItem>
                        <MenuItem className="text-secondary" value="gousse">gousse</MenuItem>
                        <MenuItem className="text-secondary" value="graine">graine</MenuItem>
                        <MenuItem className="text-secondary" value="pince">pincée</MenuItem>
                        <MenuItem className="text-secondary" value="unit">unité</MenuItem>
                </FastField>
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
