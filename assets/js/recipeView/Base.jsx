import React, {useEffect, useState} from 'react';
import Ingredient from "./components/Ingredient";
import recipe from "./Recipe";
import ingredient from "./components/Ingredient";

const Base = ({recipeIngredients}) => {

    return (
                <div className="my-1 p-2">
                    <h4>Ingredients</h4>
                    <div className="d-flex flex-row flex-wrap">
                        {recipeIngredients.map((recipeIngredient)=>{
                            return <Ingredient key={recipeIngredient.id} recipeIngredient={recipeIngredient}/>
                        })}
                    </div>
                </div>
    );
}
export default Base;