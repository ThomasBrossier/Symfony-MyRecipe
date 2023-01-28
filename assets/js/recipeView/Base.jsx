import React, { useState} from 'react';
import Ingredient from "./components/Ingredient";
import {Autocomplete, CircularProgress, ListSubheader, MenuItem, Modal, Select, TextField} from "@mui/material";
import {AuthContext, ingredientsUnit} from "../config";
import {useContext} from "react";

const Base = ({recipeIngredients}) => {
    const [openedModal, setOpenedModal] = useState(false);
    const {editMode, recipe, setRecipe , setSuccess, setSnackBarContent, switchSnackBarOpen} = useContext(AuthContext);
    const [currentIngredients, setCurrentIngredients] = useState([]);
    const [newIngredientError, setNewIngredientError] = useState('');
    const [ingredient, setIngredient ] = useState('');
    const [editIngredient, setEditIngredient ] = useState(false);
    const [recipeIngredientId, setRecipeIngredientId ] = useState('');
    const [unit, setUnit ] = useState('');
    const [quantity, setQuantity ] = useState('');
    const [loading, setLoading] = useState(false);

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

    const SaveIngredient = async (e)=>{
        if(!ingredient || !unit || ! quantity){
            setNewIngredientError('Veuillez remplir tous les champs')
        }else{
            let recipeIngredient
            let response;
            let params;
            if(editIngredient){
                recipeIngredient = recipe.recipeIngredients.filter((recipeIngredient)=> recipeIngredient.id === recipeIngredientId)[0]
                recipeIngredient.ingredient = ingredient;
                recipeIngredient.unit = unit;
                recipeIngredient.quantity = quantity
                params = {
                    body: JSON.stringify(recipeIngredient),
                    method:'POST',
                }
                response = await fetch('/api/recipe/'+ recipe.id + '/recipeIngredient', params  )
            }else{
                    recipeIngredient = {
                        id : Date.now(),
                        ingredient : ingredient,
                        quantity,
                        unit
                    }
                params = {
                    body: JSON.stringify(recipeIngredient),
                    method:'POST',
                }
                response = await fetch('/api/recipe/'+ recipe.id + '/recipeIngredient/new', params )
                setRecipe({...recipe, recipeIngredients: [...recipe.recipeIngredients, recipeIngredient]})
            }
            let res = await response.json();
            if(res.status !== "200" ){
                setSuccess(false)
            }
            setSnackBarContent(res.result);
            switchSnackBarOpen(true);
            setNewIngredientError('');

            setUnit('');
            setQuantity('');
            setIngredient('');
            setOpenedModal(false);
        }
    }
    const ingredientsUnitSelectCat = ()=> {
        const data = [];
        ingredientsUnit.forEach((cat)=>{
            data.push(  <ListSubheader key={cat.catName} className="text-black text-bg-light">{cat.catName}</ListSubheader> )
            cat.catValues.forEach((value)=>{
                data.push( <MenuItem key={value.value} className="text-secondary" value={value.value}>{value.name}</MenuItem>)
            })
        })
        return data
    }
    const closeModal = ()=>{
        setUnit('');
        setIngredient('')
        setQuantity('')
        setOpenedModal(false)
        setEditIngredient(false)
    }
    const openModal = ()=>{
        setUnit('');
        setIngredient('')
        setQuantity('')
        setEditIngredient(false)
        setOpenedModal(true)
    }
    const handleIngredientClick= (id)=>{
        setEditIngredient(true)
        recipe.recipeIngredients.map((recipeIngredient)=>{
            if(recipeIngredient.id === id){
                setRecipeIngredientId(recipeIngredient.id)
                setUnit(recipeIngredient.unit)
                setIngredient(recipeIngredient.ingredient)
                setQuantity(recipeIngredient.quantity)
            }
        })
        setOpenedModal(true);
    }

    const deleteIngredient= async ()=>{

        if(confirm("Etes vous sur de vouloir supprimer cette étape ?")) {
            const data = JSON.stringify(recipeIngredientId);
            let params = {
                body : data,
                method:'POST',
            }
            let response = await fetch('/api/recipe/'+ recipe.id + '/deleteRecipeIngredient', params)
            let res = await response.json();
            if(res.status === "200" ){
                setSuccess(true);
                const arr = [...recipe.recipeIngredients]
                const index = arr.findIndex((val)=> val.id === recipeIngredientId )
                arr.splice(index,1);
                setRecipe({...recipe, recipeIngredients : arr});
                closeModal();
            }else{
                setSuccess(false)
            }
            setSnackBarContent(res.result);
            switchSnackBarOpen(true)
        }
    }

    return (
        <>
                <div className="my-1 p-2">
                    <h4>Ingredients</h4>
                    <div className="d-flex flex-row flex-wrap">
                        {recipeIngredients.map((recipeIngredient)=>{
                            return <Ingredient key={recipeIngredient.id} handleIngredientClick={handleIngredientClick} recipeIngredient={recipeIngredient}/>
                        })}
                        <button style={{width: "80px",height: "80px"}}
                                className="btn btn-secondary m-2 mx-4"
                                onClick={()=>openModal()}>
                            <i className="m-0 fa-solid fa-plus h2 text-light"></i>
                        </button>
                    </div>
                </div>

                <Modal
                    open={openedModal}
                    onClose={()=> closeModal()}
                    aria-labelledby="modal-modal-title"
                    aria-describedby="modal-modal-description"
                >
                    <div className="my-modal">
                        <div className="d-flex justify-content-between align-items-center">
                            <h4>{editIngredient ? 'Modifier' : 'Ajouter'} un ingredient</h4>
                            <button className="btn btn-danger" onClick={()=>setOpenedModal(false)} ><i className="m-0 fa-solid fa-xmark"></i></button>
                        </div>
                        <div className="d-flex justify-content-between align-items-center">
                            <Autocomplete
                                          sx={{flex:2}}
                                          options={currentIngredients}
                                          loading={loading}
                                          value={ingredient !== ( ''|| undefined ) ? ingredient : ''}
                                          onChange={(e,value)=> setIngredient(value)}
                                          getOptionLabel={(option ) => option.name ?? ''}
                                          isOptionEqualToValue={(option ) => option.name}
                                          noOptionsText={"Cette ingredient n'est pas répertorié"}
                                          loadingText={"Chargement..."}
                                          renderInput={(params) =>(
                                              <TextField {...params}
                                                         label="Ingredient... (ex : tomate)"
                                                         onChange={(e)=>handleIngredientChange(e)}
                                                         InputProps={{
                                                              ...params.InputProps,
                                                              endAdornment: (
                                                                 <>
                                                                   {loading ? <CircularProgress color="inherit" size={20} /> : null}
                                                                   {params.InputProps.endAdornment}
                                                                 </>
                                                                 ),
                                                              }}
                                              />)}
                                          />
                            <TextField sx={{flex:1}}
                                       inputProps={{ inputMode: 'numeric', pattern: '[0-9]*' }}
                                       label="Quantité"
                                       value={quantity}
                                       onChange={(e)=> setQuantity(e.target.value)} />
                            <TextField sx={{flex:1}}
                                       defaultValue=""
                                       select
                                       value={unit}
                                       onChange={(e)=> setUnit(e.target.value)}
                                       label="Unité de mesure"
                                       >
                                {
                                    ingredientsUnitSelectCat()
                                }
                            </TextField>
                        </div>
                        {newIngredientError ? <p className="text-danger" >{newIngredientError}</p> : ''}
                        <div className="d-flex justify-content-end align-items-center">
                            {editIngredient ? <button  className="btn btn-danger m-2" type="button" onClick={deleteIngredient} >Supprimer</button> : ''}
                            <button  className="btn btn-primary my-2" type="button" onClick={SaveIngredient}>{editIngredient ? 'Modifier' : 'Ajouter'}</button>
                        </div>
                    </div>
                </Modal>
        </>
    );
}
export default Base;