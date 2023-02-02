import React, { useState} from 'react';
import {ErrorMessage, FastField, Field, getIn, isEmptyArray} from 'formik';
import {Autocomplete, CircularProgress, ListSubheader, MenuItem, Select, TextField} from "@mui/material";
import {ingredientsUnit} from "../config";

const IngredientForm = ({index, remove,setFieldValue,...props}) => {
    const [currentIngredients, setCurrentIngredients] = useState([{name: ''}])
    const [loading , setLoading ] = useState(false);
    const handleIngredientChange = (e)=>{
        setLoading(true);
        setTimeout(()=>{
            fetch('/api/recipe/ingredients?' + new URLSearchParams({
                ingredient : e.target.value,
            }))
                .then(res=> res.json())
                .then(data => {
                    setLoading(false);
                    setCurrentIngredients(data);
                });
        },500)

    }

    const ingredientsUnitSelectItems = ()=> {
        const data = [];
        ingredientsUnit.forEach((cat)=>{
            data.push(  <ListSubheader key={cat.catName} className="text-black text-bg-light">{cat.catName}</ListSubheader> )
            cat.catValues.forEach((value)=>{
                data.push( <MenuItem key={value.value} className="text-secondary" value={value.value}>{value.name}</MenuItem>)
            })
        })
        return data
    }

    return (
        <div className="d-flex flex-row my-1 align-items-start align-items-md-center justify-content-between">
            <div className="d-flex flex-column flex-md-row m-2 m-md-0 mx-md-2  flex-grow-1">
                <div className="ingredient-input">
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
                <div className="ingredient-input">
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
                <div className="ingredient-input">
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
                    >
                        {
                            ingredientsUnitSelectItems()
                        }
                    </FastField>
                </div>
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
