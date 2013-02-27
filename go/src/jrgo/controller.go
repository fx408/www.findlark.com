package jrgo

import (
	json "encoding/json"
	"fmt"
	//"net/http"

//"reflect"
)

type Controller struct {
	JrBase
	Ctx  *Request
	Form map[string]interface{}
	Data map[string]interface{}

	//ControllerType reflect.Type
}

type ControllerInterface interface {
	Init(ctx *Request)
	BeforeAction()
	AfterAction()
	ActionIndex()
}

/**
 * 初始化Controller
 */
func (this *Controller) Init(ctx *Request) {
	this.Ctx = ctx
	this.Data = make(map[string]interface{})
	this.Form = make(map[string]interface{})

	fmt.Println("-> InitController")
}

/**
 * 在调用Action 之前调用
 */
func (this *Controller) BeforeAction() {
	form := this.Ctx.Request.Form
	for k, v := range form {
		this.Form[k] = v
	}

	fmt.Println("-> BeforeAction", form)
}

/**
 * 在调用Action 之后调用，输出JSON数据到客服端
 */
func (this *Controller) AfterAction() {
	fmt.Println("-> AfterAction")
	this.Data["name"] = "name string"

	enc := json.NewEncoder(this.Ctx.ResponseWriter)
	err := enc.Encode(this.Data)

	if err != nil {
		fmt.Println("error:", err)
	}
}
